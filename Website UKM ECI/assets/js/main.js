// Handle Join Form Submission
document.addEventListener('DOMContentLoaded', function() {
    const joinForm = document.getElementById('joinForm');
    const formMessage = document.getElementById('formMessage');

    if (joinForm) {
        joinForm.addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form data
            const formData = new FormData(joinForm);

            // Show loading state
            formMessage.style.display = 'block';
            formMessage.innerHTML = 'Sending...';
            formMessage.style.backgroundColor = '#e3f2fd';
            formMessage.style.color = '#1976d2';

            // Submit form via AJAX
            fetch('submit_form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                formMessage.style.display = 'block';
                if (data.success) {
                    formMessage.innerHTML = data.message;
                    formMessage.style.backgroundColor = '#c8e6c9';
                    formMessage.style.color = '#2e7d32';
                    joinForm.reset();
                } else {
                    formMessage.innerHTML = data.message;
                    formMessage.style.backgroundColor = '#ffcdd2';
                    formMessage.style.color = '#c62828';
                }
            })
            .catch(error => {
                formMessage.style.display = 'block';
                formMessage.innerHTML = 'Error submitting form. Please try again.';
                formMessage.style.backgroundColor = '#ffcdd2';
                formMessage.style.color = '#c62828';
                console.error('Error:', error);
            });
        });
    }

    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll('nav a');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });

    // Active link highlighting
    window.addEventListener('scroll', function() {
        let current = '';
        const sections = document.querySelectorAll('section');
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (window.pageYOffset >= sectionTop - 200) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === '#' + current) {
                link.classList.add('active');
            }
        });
    });
});
