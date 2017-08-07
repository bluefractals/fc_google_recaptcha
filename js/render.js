function fcRecaptcha() {
    var els = document.getElementsByClassName("fcRecaptcha");
    for (var i = 0; i < els.length; i++) {
        var el = els[i];
        grecaptcha.render(el.getAttribute('id'), {
            'sitekey': el.getAttribute('data-sitekey'),
            'theme': el.getAttribute('data-theme')
        });
    }
}