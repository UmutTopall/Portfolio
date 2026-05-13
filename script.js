document.addEventListener("DOMContentLoaded", function () {
    const menuToggle = document.querySelector('.menu-toggle');
    const navMenu = document.querySelector('nav ul');

    menuToggle.addEventListener('click', function() {
        navMenu.classList.toggle('active');
        menuToggle.querySelector('i').classList.toggle('fa-bars');
        menuToggle.querySelector('i').classList.toggle('fa-times');
    });

    document.querySelectorAll('nav a').forEach(Link => {
        Link.addEventListener('click', function() {
            navMenu.classList.remove('active');
            menuToggle.querySelector('i').classList.add('fa-bars');
            menuToggle.querySelector('i').classList.remove('fa-times');
        });
    });

    const texts = [
        "Frontend Developer",
        "UI/UX Designer",
        "Web Consultant",
    ];
    let textIndex = 0;
    let charIndex = 0;
    let isDeleting = false;
    let typingDelay = 100;

    function type() {
        const currentText = texts[textIndex];
        const typingElement = document.querySelector(".typing-text");

        if (isDeleting) {
            typingElement.textContent = currentText.substring(0, charIndex - 1);
            charIndex--;
            typingDelay = 50;
        } else {
            typingElement.textContent = currentText.substring(0, charIndex + 1);
            charIndex++;
            typingDelay = 100;
        }

        if (!isDeleting && charIndex === currentText.length) {
            isDeleting = true;
             typingDelay = 1500;
        } else if (isDeleting && charIndex === 0) {
            isDeleting = false;
            textIndex = (textIndex + 1) % texts.length;
            typingDelay = 500;
        }

        setTimeout(type, typingDelay);
                
    }

    setTimeout(type, 1000);

    document.querySelectorAll('a[href^="#"]').forEach(anchor => { anchor.addEventListener('click', function (e) {e.preventDefault();
        const targetId = this.getAttribute('href');
        if (targetId === '#') return ;

        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }

    });
});

});

const contactForm = document.getElementById('contactForm');
const formStatus = document.getElementById('formStatus');

if (contactForm) {
    contactForm.addEventListener('submit', function(e) {
        e.preventDefault();

        
        const myFormData = new FormData(this); 

        fetch('contact.php', {
            method: 'POST',
            body: myFormData 
        })
        .then(response => response.text())
        .then(data => {
            if (data === "success") {
                formStatus.innerHTML = "<p style='color: #2afc85;'>Message sent!</p>";
                contactForm.reset();
            }
        });
    });
}

function loadProjects() {
    const projectsGrid = document.querySelector('.projects-grid');
    
    fetch('fetch_projects.php')
        .then(response => response.json())
        .then(data => {
            projectsGrid.innerHTML = ''; 
            
            data.forEach(project => {
                const projectCard = `
                    <div class="project-card">
                        <h3>${project.title}</h3>
                        <p>${project.description}</p>
                        <p><strong>Tech:</strong> ${project.tech_stack}</p>
                    </div>
                `;
                projectsGrid.innerHTML += projectCard;
            });
        })
        .catch(error => console.error('An error occurred while loading projects:', error));
}

document.addEventListener('DOMContentLoaded', loadProjects);