<?php

declare(strict_types=1);

/**
 * GitHub webhook endpoint for automatic production deployment.
 *
 * Webhook URL: https://your-domain.com/deploy.php
 * Content type: application/json
 * Event: push
 */

const DEPLOY_SECRET = 'ef2e90fab4ebfc6b583d7b9e273e1f9a6242c3631570d5844e5391cc2fc837db';
const DEPLOY_REPOSITORY = 'Shobahan758/camera';
const DEPLOY_BRANCH = 'master';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond(int $status, string $message): never
{
    http_response_code($status);
    echo json_encode(
        ['success' => $status >= 200 && $status < 300, 'message' => $message],
        JSON_UNESCAPED_SLASHES
    );
    exit;
}

function writeDeployLog(string $message): void
{
    $logFile = dirname(__DIR__).'/storage/logs/deploy.log';
    $entry = sprintf("[%s] %s\n", date('c'), str_replace(["\r", "\n"], ' ', $message));
    @file_put_contents($logFile, $entry, FILE_APPEND | LOCK_EX);
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    respond(405, 'Method not allowed.');
}

$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if ($payload === false || $payload === '' || !str_starts_with($signature, 'sha256=')) {
    respond(400, 'Invalid webhook request.');
}

$expectedSignature = 'sha256='.hash_hmac('sha256', $payload, DEPLOY_SECRET);

if (!hash_equals($expectedSignature, $signature)) {
    writeDeployLog('Rejected request with an invalid signature.');
    respond(403, 'Invalid signature.');
}

$event = $_SERVER['HTTP_X_GITHUB_EVENT'] ?? '';

if ($event === 'ping') {
    respond(200, 'Webhook is configured correctly.');
}

if ($event !== 'push') {
    respond(202, 'Event ignored.');
}

$data = json_decode($payload, true);

if (!is_array($data)) {
    respond(400, 'Invalid JSON payload.');
}

if (($data['repository']['full_name'] ?? '') !== DEPLOY_REPOSITORY) {
    respond(403, 'Repository not allowed.');
}

if (($data['ref'] ?? '') !== 'refs/heads/'.DEPLOY_BRANCH) {
    respond(202, 'Branch ignored.');
}

$projectPath = dirname(__DIR__);
$lockFile = $projectPath.'/storage/framework/deploy.lock';
$lockHandle = @fopen($lockFile, 'c');

if ($lockHandle === false) {
    writeDeployLog('Could not open the deployment lock file.');
    respond(500, 'Could not start deployment.');
}

if (!flock($lockHandle, LOCK_EX | LOCK_NB)) {
    fclose($lockHandle);
    respond(409, 'Another deployment is already running.');
}

$command = sprintf(
    'git -C %s pull --ff-only origin %s 2>&1',
    escapeshellarg($projectPath),
    escapeshellarg(DEPLOY_BRANCH)
);

$output = [];
$exitCode = 1;
exec($command, $output, $exitCode);

flock($lockHandle, LOCK_UN);
fclose($lockHandle);

$summary = trim(implode(' | ', $output));
writeDeployLog(sprintf('Git pull exited with code %d: %s', $exitCode, $summary));

if ($exitCode !== 0) {
    respond(500, 'Deployment failed. Check storage/logs/deploy.log.');
}

respond(200, 'Deployment completed successfully.');
