/**
 * ANAND KUMAR - PORTFOLIO JAVASCRIPT
 * Full Interactivity, Theme Management, Mobile Navigation,
 * Animations, Certificate Modal, and Serverless Contact Handler
 */

document.addEventListener("DOMContentLoaded", () => {
    // ==========================================
    // 1. THEME TOGGLE (Dark / Light Mode)
    // ==========================================
    const themeToggleBtn = document.getElementById("themeToggle");
    const themeIcon = document.getElementById("themeIcon");

    // Initialize saved theme or default to dark
    const savedTheme = localStorage.getItem("theme") || "dark";
    if (savedTheme === "light") {
        document.body.classList.add("light-mode");
        if (themeIcon) {
            themeIcon.classList.replace("fa-moon", "fa-sun");
        }
    }

    if (themeToggleBtn) {
        themeToggleBtn.addEventListener("click", () => {
            document.body.classList.toggle("light-mode");
            const isLight = document.body.classList.contains("light-mode");

            if (themeIcon) {
                if (isLight) {
                    themeIcon.classList.replace("fa-moon", "fa-sun");
                } else {
                    themeIcon.classList.replace("fa-sun", "fa-moon");
                }
            }

            localStorage.setItem("theme", isLight ? "light" : "dark");
        });
    }

    // ==========================================
    // 2. MOBILE NAVIGATION DRAWER
    // ==========================================
    const menuBtn = document.getElementById("menuBtn");
    const menuIcon = document.getElementById("menuIcon");
    const navbar = document.getElementById("navbar");
    const navOverlay = document.getElementById("navOverlay");
    const navLinks = document.querySelectorAll(".nav-link");

    function openMobileMenu() {
        navbar.classList.add("active");
        navOverlay.classList.add("active");
        document.body.classList.add("menu-open");
        if (menuIcon) {
            menuIcon.classList.replace("fa-bars", "fa-xmark");
        }
    }

    function closeMobileMenu() {
        navbar.classList.remove("active");
        navOverlay.classList.remove("active");
        document.body.classList.remove("menu-open");
        if (menuIcon) {
            menuIcon.classList.replace("fa-xmark", "fa-bars");
        }
    }

    if (menuBtn) {
        menuBtn.addEventListener("click", () => {
            if (navbar.classList.contains("active")) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });
    }

    if (navOverlay) {
        navOverlay.addEventListener("click", closeMobileMenu);
    }

    navLinks.forEach((link) => {
        link.addEventListener("click", () => {
            closeMobileMenu();
        });
    });

    // Close on ESC key
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            closeMobileMenu();
            closeCertModal();
        }
    });

    // ==========================================
    // 3. HEADER SCROLL & SCROLLSPY
    // ==========================================
    const header = document.getElementById("header");
    const backToTopBtn = document.getElementById("backToTop");
    const sections = document.querySelectorAll("section[id]");

    window.addEventListener("scroll", () => {
        const scrollY = window.pageYOffset;

        // Sticky Header effect
        if (header) {
            if (scrollY > 30) {
                header.classList.add("scrolled");
            } else {
                header.classList.remove("scrolled");
            }
        }

        // Back to Top button visibility
        if (backToTopBtn) {
            if (scrollY > 400) {
                backToTopBtn.classList.add("show");
            } else {
                backToTopBtn.classList.remove("show");
            }
        }

        // Active Section Scrollspy
        sections.forEach((section) => {
            const sectionTop = section.offsetTop - 120;
            const sectionHeight = section.offsetHeight;
            const sectionId = section.getAttribute("id");

            if (scrollY >= sectionTop && scrollY < sectionTop + sectionHeight) {
                navLinks.forEach((link) => {
                    link.classList.remove("active");
                    if (link.getAttribute("href") === `#${sectionId}`) {
                        link.classList.add("active");
                    }
                });
            }
        });
    });

    // Back to top click handler
    if (backToTopBtn) {
        backToTopBtn.addEventListener("click", () => {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        });
    }

    // ==========================================
    // 4. TYPEWRITER EFFECT (Hero Section)
    // ==========================================
    const typewriterElement = document.getElementById("typewriterText");
    const phrases = [
        "Data Analytics Enthusiast 📊",
        "Python & SQL Developer 💻",
        "Open Source Campus Lead (OSCI'26) 🚀",
        "Aspiring Software Developer ⚡",
        "Power BI & Excel Specialist 📈"
    ];

    let phraseIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    const typeSpeed = 80;
    const deleteSpeed = 40;
    const pauseDelay = 1800;

    function typeWriter() {
        if (!typewriterElement) return;

        const currentPhrase = phrases[phraseIndex];

        if (isDeleting) {
            typewriterElement.textContent = currentPhrase.substring(0, charIndex - 1);
            charIndex--;
        } else {
            typewriterElement.textContent = currentPhrase.substring(0, charIndex + 1);
            charIndex++;
        }

        let speed = isDeleting ? deleteSpeed : typeSpeed;

        if (!isDeleting && charIndex === currentPhrase.length) {
            speed = pauseDelay;
            isDeleting = true;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            phraseIndex = (phraseIndex + 1) % phrases.length;
            speed = 400;
        }

        setTimeout(typeWriter, speed);
    }

    if (typewriterElement) {
        typeWriter();
    }

    // ==========================================
    // 5. SCROLL REVEAL ANIMATION (Intersection Observer)
    // ==========================================
    const revealElements = document.querySelectorAll(".reveal");

    if ("IntersectionObserver" in window) {
        const revealObserver = new IntersectionObserver(
            (entries, observer) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add("visible");
                        observer.unobserve(entry.target);
                    }
                });
            },
            {
                root: null,
                threshold: 0.12,
                rootMargin: "0px 0px -40px 0px"
            }
        );

        revealElements.forEach((el) => revealObserver.observe(el));
    } else {
        // Fallback for older browsers
        revealElements.forEach((el) => el.classList.add("visible"));
    }

    // ==========================================
    // 6. ANIMATED NUMBER COUNTERS
    // ==========================================
    const countElements = document.querySelectorAll(".count-up");

    function animateCounters() {
        countElements.forEach((counter) => {
            const target = +counter.getAttribute("data-target");
            const duration = 1500; // ms
            const stepTime = 30;
            const steps = duration / stepTime;
            const increment = target / steps;
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = `${target}+`;
                    clearInterval(timer);
                } else {
                    counter.textContent = `${Math.ceil(current)}+`;
                }
            }, stepTime);
        });
    }

    if (countElements.length > 0 && "IntersectionObserver" in window) {
        const statsSection = document.querySelector(".about-stats");
        if (statsSection) {
            const statsObserver = new IntersectionObserver(
                (entries, observer) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            animateCounters();
                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.3 }
            );
            statsObserver.observe(statsSection);
        }
    }

    // ==========================================
    // 7. SKILLS CATEGORY FILTER
    // ==========================================
    const filterButtons = document.querySelectorAll(".filter-btn");
    const skillCards = document.querySelectorAll(".skill-card");

    filterButtons.forEach((button) => {
        button.addEventListener("click", () => {
            filterButtons.forEach((btn) => btn.classList.remove("active"));
            button.classList.add("active");

            const filterValue = button.getAttribute("data-filter");

            skillCards.forEach((card) => {
                const cardCategory = card.getAttribute("data-category") || "";

                if (filterValue === "all" || cardCategory.includes(filterValue)) {
                    card.style.display = "flex";
                    setTimeout(() => {
                        card.style.opacity = "1";
                        card.style.transform = "scale(1)";
                    }, 50);
                } else {
                    card.style.opacity = "0";
                    card.style.transform = "scale(0.95)";
                    setTimeout(() => {
                        card.style.display = "none";
                    }, 250);
                }
            });
        });
    });

    // ==========================================
    // 8. CERTIFICATE LIGHTBOX MODAL
    // ==========================================
    const certModal = document.getElementById("certModal");
    const certModalImg = document.getElementById("certModalImg");
    const certModalTitle = document.getElementById("certModalTitle");
    const certModalIssuer = document.getElementById("certModalIssuer");
    const certModalDownload = document.getElementById("certModalDownload");
    const certModalClose = document.getElementById("certModalClose");
    const certCards = document.querySelectorAll(".certificate-card");

    function openCertModal(certSrc, title, issuer) {
        if (!certModal) return;
        certModalImg.src = certSrc;
        certModalTitle.textContent = title || "Certificate Preview";
        certModalIssuer.textContent = issuer || "Verified Credential";
        certModalDownload.href = certSrc;

        certModal.classList.add("active");
        certModal.setAttribute("aria-hidden", "false");
        document.body.style.overflow = "hidden";
    }

    function closeCertModal() {
        if (!certModal) return;
        certModal.classList.remove("active");
        certModal.setAttribute("aria-hidden", "true");
        document.body.style.overflow = "";
    }

    certCards.forEach((card) => {
        card.addEventListener("click", () => {
            const certSrc = card.getAttribute("data-cert");
            const title = card.getAttribute("data-title");
            const issuer = card.getAttribute("data-issuer");
            if (certSrc) {
                openCertModal(certSrc, title, issuer);
            }
        });
    });

    if (certModalClose) {
        certModalClose.addEventListener("click", closeCertModal);
    }

    if (certModal) {
        certModal.addEventListener("click", (e) => {
            if (e.target === certModal) {
                closeCertModal();
            }
        });
    }

    // ==========================================
    // 9. REAL-TIME EMAIL CONTACT FORM HANDLER
    // (Direct inbox delivery to anandgupta875728@gmail.com)
    // ==========================================
    const contactForm = document.getElementById("contact-form");
    const formToast = document.getElementById("formToast");
    const toastIcon = document.getElementById("toastIcon");
    const toastMessage = document.getElementById("toastMessage");
    const submitBtn = document.getElementById("submitBtn");

    function showToast(message, isSuccess = true) {
        if (!formToast) return;

        formToast.className = `form-toast ${isSuccess ? "success" : "error"}`;
        if (toastIcon) {
            toastIcon.className = isSuccess
                ? "fa-solid fa-circle-check"
                : "fa-solid fa-circle-exclamation";
        }
        if (toastMessage) {
            toastMessage.textContent = message;
        }

        formToast.style.display = "flex";

        setTimeout(() => {
            formToast.style.display = "none";
        }, 7000);
    }

    if (contactForm) {
        contactForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const name = document.getElementById("name").value.trim();
            const email = document.getElementById("email").value.trim();
            const subject = document.getElementById("subject").value.trim();
            const message = document.getElementById("message").value.trim();

            if (!name || !email || !subject || !message) {
                showToast("Please fill in all required fields.", false);
                return;
            }

            // Set subject field for email header
            const subjectInput = document.getElementById("formSubmitSubject");
            if (subjectInput) {
                subjectInput.value = `Portfolio Contact: ${subject} (from ${name})`;
            }

            // Button loading state
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <i class="fa-solid fa-spinner fa-spin"></i>
                <span>Sending to Anand's Email...</span>
            `;

            try {
                // Prepare form data for FormSubmit API
                const formData = new FormData(contactForm);

                const response = await fetch("https://formsubmit.co/ajax/anandgupta875728@gmail.com", {
                    method: "POST",
                    headers: {
                        "Accept": "application/json"
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok || data.success === "true" || data.success === true) {
                    showToast(`Thank you, ${name}! Your message has been sent directly to Anand's email (anandgupta875728@gmail.com).`, true);
                    contactForm.reset();
                } else {
                    throw new Error(data.message || "Failed to send message via email service.");
                }

                // Also silently record to local PHP if running locally
                try {
                    fetch("php/contact.php", { method: "POST", body: formData });
                } catch (phpErr) {
                    // Ignore PHP error on static hosting
                }

            } catch (error) {
                console.error("Email delivery error:", error);
                
                // Fallback: Open mailto link so user message is never lost
                showToast(`Sending automated email encountered an issue. Opening your email app to send directly...`, false);
                
                setTimeout(() => {
                    const mailtoUrl = `mailto:anandgupta875728@gmail.com?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent("Name: " + name + "\nEmail: " + email + "\n\nMessage:\n" + message)}`;
                    window.location.href = mailtoUrl;
                }, 1500);

            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        });
    }
});