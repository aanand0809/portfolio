// ================================
// DARK / LIGHT MODE
// ================================

const themeBtn = document.querySelector(".theme-btn");

if (themeBtn) {

    themeBtn.addEventListener("click", function () {

        document.body.classList.toggle("light-mode");

        if (document.body.classList.contains("light-mode")) {
            themeBtn.innerHTML = "☀️";
            localStorage.setItem("theme", "light");
        } else {
            themeBtn.innerHTML = "🌙";
            localStorage.setItem("theme", "dark");
        }

    });

    // Save theme after page reload
    if (localStorage.getItem("theme") === "light") {
        document.body.classList.add("light-mode");
        themeBtn.innerHTML = "☀️";
    }
}
// ================================
// CONTACT FORM
// ================================

const contactForm = document.getElementById("contact-form");

if (contactForm) {

    contactForm.addEventListener("submit", async function (event) {

        // Normal form submit रोकना
        event.preventDefault();

        const submitButton =
            contactForm.querySelector('button[type="submit"]');

        const originalText = submitButton.innerHTML;

        // Sending
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <i class="fa-solid fa-spinner fa-spin"></i>
            Sending...
        `;

        // पुराने message को हटाओ
        const oldMessage =
            contactForm.querySelector(".form-message");

        if (oldMessage) {
            oldMessage.remove();
        }

        try {

            const formData = new FormData(contactForm);

            const response = await fetch("php/contact.php", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            console.log("PHP Response:", result);

            const messageBox =
                document.createElement("div");

            if (result.success) {

                messageBox.className =
                    "form-message success-message";

                messageBox.innerHTML = `
                    <i class="fa-solid fa-circle-check"></i>
                    <span>${result.message}</span>
                `;

                contactForm.insertBefore(
                    messageBox,
                    contactForm.firstChild
                );

                // Form clear
                contactForm.reset();

            } else {

                messageBox.className =
                    "form-message error-message";

                messageBox.innerHTML = `
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>${result.message}</span>
                `;

                contactForm.insertBefore(
                    messageBox,
                    contactForm.firstChild
                );
            }

            // 5 seconds baad message remove
            setTimeout(() => {
                if (messageBox) {
                    messageBox.remove();
                }
            }, 5000);

        } catch (error) {

            console.error("Contact Form Error:", error);

            const errorBox =
                document.createElement("div");

            errorBox.className =
                "form-message error-message";

            errorBox.innerHTML = `
                <i class="fa-solid fa-circle-exclamation"></i>
                <span>
                    Unable to send message. Please try again.
                </span>
            `;

            contactForm.insertBefore(
                errorBox,
                contactForm.firstChild
            );

        } finally {

            submitButton.disabled = false;
            submitButton.innerHTML = originalText;

        }

    });

}