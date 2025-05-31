
const arr = [];
arr[0] = '';
for(i = 1; i <= 100; i++) {
    arr[i] = `"./wallpapers_change/image (${i}).jpg"`;
}


console.log(arr[50])


// hide Header on on scroll down
var didScroll;
var lastScrollTop = 0;
var delta = 5;
var navbarHeight = $('header').outerHeight();

$(window).scroll(function(event){
    didScroll = true;
});

setInterval(function() {
    if (didScroll) {
        hasScrolled();
        didScroll = false;
    }
}, 250);

function hasScrolled() {
    var st = $(this).scrollTop();
    
    // make sure they scroll more than delta
    if(Math.abs(lastScrollTop - st) <= delta)
        return;
    
    if (st > lastScrollTop && st > navbarHeight){
        // scroll Down
        $('header').removeClass('nav-down').addClass('nav-up');
    } else {
        // scroll Up
        if(st + $(window).height() < $(document).height()) {
            $('header').removeClass('nav-up').addClass('nav-down');
        }
    }
    
    lastScrollTop = st;
}
