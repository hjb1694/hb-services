<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Hayden Bradfield provides web development services, custom websites, and more!" />
    <meta name="keywords" content="Hayden, Bradfield, web, development"/>
    <meta property="og:site_name" content="Hayden Bradfield Web Services" />
    <meta property="og:title" content="Hayden Bradfield Web Services" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="https://services.haydenbradfield.com" />
    <meta property="og:image" content="https://services.haydenbradfield.com/public/img/web_services_img.png" /> 
    <title>Hayden Bradfield - Independant Web Services</title>
    <link rel="stylesheet" type="text/css" href="/public/css/style.css?v=4" />
</head>
<body>
    <header class="header">
        <h1 class="header__logo"><img src="/public/icons/code_icon.svg" class="header__logo-icon"/> Hayden Bradfield</h1>
        <h2 class="header__subtext">Independant Web Development Services</h2>
    </header>
    <section class="hero">
        <video class="hero__bg-vid" src="/public/videos/output.mp4" autoplay loop muted preload="auto" playsinline></video>
        <h3 class="hero__text">Take your idea to the digital world</h3>
        <a class="hero__action-btn" href="#services">View Services</a>
    </section>
    <section class="services" id="services">
        <div class="service-tile-collection">
            <div class="service-tile">
                <img src="/public/icons/code_3.svg" class="service-tile__icon" />
                <h4 class="service-tile__text">Custom Websites</h4>
            </div>
            <div class="service-tile">
                <img src="/public/icons/search_up.svg" class="service-tile__icon" />
                <h4 class="service-tile__text">SEO</h4>
            </div>
            <div class="service-tile">
                <img src="/public/icons/pwa.svg" class="service-tile__icon" />
                <h4 class="service-tile__text">PWA Development</h4>
            </div>
            <div class="service-tile">
                <img src="/public/icons/head_idea.svg" class="service-tile__icon" />
                <h4 class="service-tile__text">Consulting</h4>
            </div>
            <div class="service-tile">
                <img src="/public/icons/mobile.svg" class="service-tile__icon" />
                <h4 class="service-tile__text">Hybrid Apps</h4>
            </div>
        </div>
    </section>
    <section class="benefits">
        <ul class="benefit-list">
            <li class="benefit-item">
                <img class="checkmark-icon" src="/public/icons/blue_check.svg" alt="blue checkmark icon" />
                <p class="benefit-item__text">Affordable</p>
            </li>
            <li class="benefit-item">
                <img class="checkmark-icon" src="/public/icons/blue_check.svg" alt="blue checkmark icon" />
                <p class="benefit-item__text">Dependable</p>
            </li>
            <li class="benefit-item">
                <img class="checkmark-icon" src="/public/icons/blue_check.svg" alt="blue checkmark icon" />
                <p class="benefit-item__text">Communicative</p>
            </li>
            <li class="benefit-item">
                <img class="checkmark-icon" src="/public/icons/blue_check.svg" alt="blue checkmark icon" />
                <p class="benefit-item__text">Made in the USA!</p>
            </li>
        </ul>
    </section>
    <section class="contact">
        <h2 class="contact__heading">Inquire Today</h2>
        <form class="contact-form">
            <div class="form-group">
                <div class="text-field">
                    <label class="text-field__label" for="fullname">Name</label>
                    <input class="text-field__input" type="text" id="fullname" maxlength="50"/>
                </div>
            </div>
            <div class="form-group">
                <div class="text-field">
                    <label class="text-field__label" for="email">Email</label>
                    <input class="text-field__input" type="text" id="email" maxlength="150"/>
                </div>
            </div>
            <div class="form-group">
                <textarea class="message-area" placeholder="Type your message here :)" id="message" maxlength="1000"></textarea>
            </div>
            <div class="form-group">
                <button class="subbut">Submit <img src="/public/icons/send_icon.svg" alt="send icon" /></button>
            </div>
            <ul class="errbox">

            </ul>
        </form>
    </section>
    <footer class="footer">
        <p><small>&copy; Hayden Bradfield</small></p>
        <p><a href="https://www.haydenbradfield.com">www.haydenbradfield.com</a></p>
    </footer>
    <script>
        const contactForm = document.querySelector('.contact-form');
        const errbox = document.querySelector('.errbox');
        const subbut = document.querySelector('.subbut');

        const submitForm = async e => {
            e.preventDefault();
            errbox.innerHTML = '';
            errbox.classList.remove('show');
            const errors = [];

            const fields = {
                fullName: document.querySelector('#fullname'),
                email: document.querySelector('#email'),
                message: document.querySelector('#message')
            }
            const emailRegExp = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/gi;

            (fields.fullName.value.trim().length < 2) && errors.push('Please enter a valid name.');
            (!emailRegExp.test(fields.email.value.trim())) && errors.push('Please enter a valid email address.');
            (fields.message.value.trim().length < 10) && errors.push('Please include a longer message.');

            if(errors.length){
                for(let error of errors){
                    errbox.insertAdjacentHTML('beforeend', `<li>${error}</li>`);
                }
                errbox.classList.add('show');
                return;
            }

            subbut.innerHTML = "Sending...";

           
            fetch('https://services.haydenbradfield.com/process_contact_form.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'appplication/json'
            },
            body: JSON.stringify({
                full_name: fields.fullName.value.trim(),
                email: fields.email.value.trim(),
                message: fields.message.value.trim()
            })
            }).then(response => {
                if(!response.ok){
                    throw new Error();
                }
                alert('Successfully submitted!');
            }).catch(e => {
                console.error(e);
                errbox.insertAdjacentHTML('beforeend', '<li>An error has occurred.</li>');
                errbox.classList.add('show');
            }).finally(() => {
                subbut.innerHTML = `Submit <img src="/public/icons/send_icon.svg" alt="send icon" />`;
            });
               

        }

        contactForm.addEventListener('submit', submitForm);
    </script>
</body>
</html>