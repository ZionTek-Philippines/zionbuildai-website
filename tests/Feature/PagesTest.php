<?php

test('every company and legal page loads with its own title, the shared header and footer, and analytics', function (string $routeName, string $title, string $content) {
    $this->get(route($routeName))
        ->assertOk()
        ->assertSee("<title>{$title} | ", false)
        ->assertSee($content)
        ->assertSee('data-track-section="Header"', false)
        ->assertSee('data-track-section="Footer"', false)
        ->assertSee('name="site-analytics"', false);
})->with([
    ['about', 'About Us', 'Building better lives through'],
    ['contact', 'Contact Us', 'Phase 8, Cainta Greenland Executive Village'],
    ['privacy-policy', 'Privacy Policy', 'Data Privacy Act of 2012'],
    ['terms-of-service', 'Terms of Service', 'fictional sample data'],
    ['data-security', 'Data Security', 'Role-based access'],
]);

test('the contact page shows every way to reach us', function () {
    $this->get(route('contact'))
        ->assertSee('href="tel:+639617858547"', false)
        ->assertSee('href="mailto:hello@ziontek.co"', false)
        ->assertSee('href="https://facebook.com/ziontekph"', false)
        ->assertSee('9:00 AM to 5:00 PM');
});

test('footer links point to real pages instead of empty anchors', function () {
    $this->get('/')
        ->assertSee('href="'.route('about').'"', false)
        ->assertSee('href="'.route('contact').'"', false)
        ->assertSee('href="'.route('privacy-policy').'"', false)
        ->assertSee('href="'.route('terms-of-service').'"', false)
        ->assertSee('href="'.route('data-security').'"', false)
        ->assertDontSee('href="#privacy"', false)
        ->assertDontSee('href="#about"', false)
        ->assertDontSee('href="#"', false);
});

test('header links work from any page by pointing at homepage sections', function () {
    $this->get(route('about'))
        ->assertSee('href="'.url('/').'#features"', false)
        ->assertSee('href="'.url('/').'#demo"', false);
});

test('the homepage still renders every section through the shared layout', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('<title>'.config('app.name').' — Construction Management for Philippine Builders</title>', false)
        ->assertSee('data-track-section="Hero"', false)
        ->assertSee('data-track-section="Book a Demo"', false)
        ->assertSee('data-try-it-modal', false);
});

test('the about page asks search engines not to index it and keeps team names out of the html', function () {
    $this->get(route('about'))
        ->assertSee('<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">', false)
        ->assertSee('The people behind ZionBuild.')
        ->assertSee('data-masked="'.base64_encode(strrev('Andrea Jimenez')).'"', false)
        ->assertDontSee('Regie Padilla Ebalobor')
        ->assertDontSee('Andrea Jimenez');
});

test('other pages stay indexable', function () {
    $this->get(route('contact'))->assertDontSee('name="robots"', false);
});
