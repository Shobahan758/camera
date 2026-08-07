document.documentElement.classList.add("js");

(() => {
    "use strict";

    const banglaDigits = "০১২৩৪৫৬৭৮৯";
    const formatBangla = (value) =>
        String(value).replace(/\d/g, (digit) => banglaDigits[digit]);
    const formatPrice = (value) =>
        `৳${formatBangla(new Intl.NumberFormat("en-US").format(value))}`;

    const navbar = document.querySelector(".navbar");
    const updateNavbar = () =>
        navbar?.classList.toggle("scrolled", window.scrollY > 12);
    updateNavbar();
    window.addEventListener("scroll", updateNavbar, { passive: true });

    const reveals = document.querySelectorAll(".reveal");
    if ("IntersectionObserver" in window) {
        const revealObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add("visible");
                    observer.unobserve(entry.target);
                });
            },
            { threshold: 0.12 },
        );
        reveals.forEach((element) => revealObserver.observe(element));
    } else {
        reveals.forEach((element) => element.classList.add("visible"));
    }

    const counters = document.querySelectorAll(".counter");
    const animateCounter = (element) => {
        const target = Number(element.dataset.target);
        if (!Number.isFinite(target)) return;
        const duration = 1200;
        const start = performance.now();
        const tick = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            element.textContent = formatBangla(Math.round(target * eased));
            if (progress < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    };
    if ("IntersectionObserver" in window) {
        const counterObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                });
            },
            { threshold: 0.5 },
        );
        counters.forEach((counter) => counterObserver.observe(counter));
    } else {
        counters.forEach(animateCounter);
    }

    const menu = document.querySelector("#menu");
    document.querySelectorAll("#menu .nav-link").forEach((link) => {
        link.addEventListener("click", () => {
            if (window.innerWidth >= 992 || !menu?.classList.contains("show"))
                return;
            window.bootstrap?.Collapse.getOrCreateInstance(menu).hide();
        });
    });

    const lightbox = document.querySelector("#lightbox");
    const lightboxImage = lightbox?.querySelector("img");
    const closeLightboxButton = lightbox?.querySelector("button");
    let lightboxTrigger = null;
    const closeLightbox = () => {
        if (!lightbox?.classList.contains("show")) return;
        lightbox.classList.remove("show");
        document.body.classList.remove("lightbox-open");
        lightboxTrigger?.focus();
    };
    document.querySelectorAll("[data-lightbox]").forEach((link) => {
        link.addEventListener("click", (event) => {
            event.preventDefault();
            if (!lightbox || !lightboxImage) return;
            lightboxTrigger = link;
            lightboxImage.src = link.href;
            lightboxImage.alt =
                link.querySelector("img")?.alt || "গ্যালারির বড় ছবি";
            lightbox.classList.add("show");
            document.body.classList.add("lightbox-open");
            closeLightboxButton?.focus();
        });
    });
    closeLightboxButton?.addEventListener("click", closeLightbox);
    lightbox?.addEventListener("click", (event) => {
        if (event.target === lightbox) closeLightbox();
    });
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") closeLightbox();
    });

    const quantity = document.querySelector("#qty");
    const subtotal = document.querySelector("#subtotal");
    const total = document.querySelector("#total");
    const unitPrice = 13990;
    const updateTotal = () => {
        const selectedQuantity = Math.max(1, Math.trunc(Number(quantity?.value) || 1));
        if (quantity) quantity.value = selectedQuantity;
        const amount = unitPrice * selectedQuantity;
        if (subtotal) subtotal.textContent = formatPrice(amount);
        if (total) total.textContent = formatPrice(amount);
    };
    quantity?.addEventListener("input", updateTotal);
    quantity?.addEventListener("change", updateTotal);
    document.querySelectorAll("[data-quantity-action]").forEach((button) => {
        button.addEventListener("click", () => {
            if (!quantity) return;
            const current = Math.max(1, Math.trunc(Number(quantity.value) || 1));
            quantity.value =
                button.dataset.quantityAction === "increase"
                    ? current + 1
                    : Math.max(1, current - 1);
            updateTotal();
        });
    });
    updateTotal();

    const form = document.querySelector("#orderForm");
    const toast = document.querySelector("#toast");
    const incompleteTokenInput = document.querySelector("#incompleteToken");
    const incompleteStorageKey = "drishyapro_incomplete_order_token";
    const createUuid = () =>
        globalThis.crypto?.randomUUID?.() ||
        "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, (character) => {
            const random = Math.floor(Math.random() * 16);
            const value = character === "x" ? random : (random & 3) | 8;
            return value.toString(16);
        });
    const ensureIncompleteToken = () => {
        let token = sessionStorage.getItem(incompleteStorageKey);
        if (!token) {
            token = createUuid();
            sessionStorage.setItem(incompleteStorageKey, token);
        }
        if (incompleteTokenInput) incompleteTokenInput.value = token;
        return token;
    };
    ensureIncompleteToken();

    let incompleteTimer;
    const saveIncompleteOrder = async () => {
        if (!form?.dataset.incompleteUrl) return;
        const name = form.elements.name?.value.trim() || "";
        const phone = (form.elements.phone?.value || "")
            .replace(/[০-৯]/g, (digit) => banglaDigits.indexOf(digit))
            .replace(/\D/g, "")
            .replace(/^88(?=01)/, "");
        if (name.length < 2 || !/^01[3-9]\d{8}$/.test(phone)) return;

        const payload = new FormData();
        payload.set("_token", form.elements._token?.value || "");
        payload.set("token", ensureIncompleteToken());
        payload.set("name", name);
        payload.set("phone", phone);
        payload.set("email", form.elements.email?.value.trim() || "");
        payload.set("address", form.elements.address?.value.trim() || "");
        payload.set("quantity", quantity?.value || "1");

        try {
            await fetch(form.dataset.incompleteUrl, {
                method: "POST",
                body: payload,
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
                keepalive: true,
            });
        } catch {
            // The main order flow remains available if background saving fails.
        }
    };
    ["name", "phone", "email", "address", "quantity"].forEach((fieldName) => {
        form?.elements[fieldName]?.addEventListener("input", () => {
            clearTimeout(incompleteTimer);
            incompleteTimer = setTimeout(saveIncompleteOrder, 800);
        });
    });

    let toastTimer;
    form?.addEventListener("submit", async (event) => {
        event.preventDefault();
        form.classList.add("was-validated");
        if (!form.checkValidity()) {
            form.querySelector(":invalid")?.focus();
            return;
        }

        const submitButton = form.querySelector('[type="submit"]');
        const originalLabel = submitButton?.innerHTML;
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = "অর্ডার পাঠানো হচ্ছে…";
        }

        try {
            const response = await fetch(form.action, {
                method: "POST",
                body: new FormData(form),
                headers: {
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });
            const result = await response.json();

            if (!response.ok) {
                const firstError = Object.values(result.errors || {}).flat()[0];
                throw new Error(firstError || result.message || "অর্ডার পাঠানো যায়নি।");
            }

            if (toast) {
                toast.innerHTML = `<i class="bi bi-check-circle-fill me-2"></i>${result.message}`;
                toast.classList.add("show");
            }
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast?.classList.remove("show"), 4500);
            form.reset();
            form.classList.remove("was-validated");
            sessionStorage.removeItem(incompleteStorageKey);
            ensureIncompleteToken();
            updateTotal();
        } catch (error) {
            if (toast) {
                toast.textContent = error.message;
                toast.classList.add("show", "error");
            }
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => toast?.classList.remove("show", "error"), 5000);
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = originalLabel;
            }
        }
    });
})();
