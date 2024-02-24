//Navigation
!function (i) { i.fn.menumaker = function (n) { var e = i(this), s = i.extend({ title: "Menu", format: "language-changer", sticky: !1 }, n); return this.each(function () { return e.find("li ul, li .mega-dropdown").parent().addClass("has-sub"), multiTg = function () { e.find(".has-sub").prepend('<button class="submenu-button"  aria-expanded="true" aria-label="sub menu"></button>'), e.find(".submenu-button").on("click", function () { $(i(this)).text(($(i(this)).text() == '') ? i(this).next().text() : ''); i(this).toggleClass("submenu-opened"), i(this).parent().toggleClass("parent-active"), i(this).siblings("ul,.mega-dropdown").hasClass("open") ? i(this).siblings("ul,.mega-dropdown").removeClass("open").hide() : i(this).siblings("ul,.mega-dropdown").addClass("open").show() }) }, "multitoggle" === s.format ? multiTg() : e.addClass("language-changer"), s.sticky === !0 && e.css("position", "fixed"), resizeFix = function () { i(window).width() > 1024 && e.find("ul,.mega-dropdown").show(), i(window).width() <= 1024 && e.find("ul,.mega-dropdown").hide().removeClass("open") }, function () { $(window).width() > 1024 ? (resizeFix("resize"), i(window).on(resizeFix)) : (resizeFix(), i(window).on(resizeFix)) } }) } }($), function (i) { i(document).ready(function () { $(".mega-dropdown").parent().addClass("has-mega"), i("#sidebar-nav").menumaker({ title: "", format: "multitoggle" }) }) }(jQuery);
// For small screen Nav
jQuery(document).click(function (a) {
    var f = jQuery(a.target).closest(".nav-trigger"), g = jQuery(a.target).closest("#sidebar-nav"); f.length ? (a.preventDefault(), jQuery("html").toggleClass('sidebar_active'), jQuery(".nav-trigger").toggleClass("closemenu")) : g.length || (jQuery("html").removeClass('sidebar_active'), jQuery(".nav-trigger").removeClass("closemenu"))
});
// For menu position
function isTouchDevice() { return true == ("ontouchstart" in window || window.DocumentTouch && document instanceof DocumentTouch); } if (isTouchDevice() === true) { } else { jQuery(window).on('load', function () { setTimeout(function () { jQuery("ul.nav > li.has-sub").hover(function () { var s = jQuery("header").offset(), t = jQuery("header").width(), e = s.left + t, i = jQuery(this).find(".submenu-button").siblings("ul").offset(), n = jQuery(this).find(".submenu-button").siblings("ul").width(); e < i.left + n && jQuery(this).addClass("align-left-menu") }, function () { jQuery(this).removeClass("align-left-menu") }), jQuery("ul.nav ul li.has-sub").hover(function () { var s = jQuery("header").offset(), t = jQuery("header").width(), e = s.left, i = e + t, n = jQuery(this).find(".submenu-button").siblings("ul").offset(), l = jQuery(this).find(".submenu-button").siblings("ul").width(), u = n.left; i < u + l && (jQuery(this).addClass("align-left-menu")), e > u && jQuery(this).addClass("align-right-menu") }, function () { jQuery(this).removeClass("align-left-menu"), jQuery(this).removeClass("align-right-menu") }), jQuery(".nav-wrap nav ul.nav ul .has-sub").each(function (iz) { jQuery(this).css("z-index", "500" - iz); }); }, 200); }); }
navFuc = function () {
    $(".nav-wrap nav ul.nav > li").each(function () {
        // wheight=$( window ).height();
        wwidth = $(window).width();
        pwidth = $("header .container").width();
        dwidth = wwidth - pwidth;
        thewidth = dwidth / 2;
        thePos = $(this).offset().left - thewidth;
        $(this).find(".mega-dropdown").css("left", - thePos - 1)
        $(".mega-dropdown").css("width", pwidth);

        // $(".nav-wrap .mega-dropdown").css("height",wheight);
        //    $(".nav-wrap nav ul.nav ul").css("height",wheight);

    });
};
$(document).ready(function () {
    navFuc();
    setTimeout(function () {
        navFuc();
    }, 300);
});
$(window).resize(function () {
    navFuc();
    setTimeout(function () {
        navFuc();
    }, 300);
});
//Navigation End

// Menu Accordion Mobile
$('.mnu-acc').append('<span class="mnu-acc-trg"><i class="fas fa-chevron-down"></i></span>');
$('.mnu-acc-trg').click(function () {
    $(this).parent().next('.content').slideToggle(250);
    $(this).parent().toggleClass('active');
    return false;
});

// Country Mega Menu
function countryMegaMenu() {
    if ($(window).width() > 1024) {
        $('.header-top, .country-megamenu .close, .country-wrap').click(function () {
            $('html').toggleClass('country-megaActive');
            $('.country-megamenu').slideToggle(300);
            $('.country-megamenu, .header-top').toggleClass('active');
            return false;
        });
        $(document).on('click touchstart', function (fd) {
            var click_box = $(".header-top, .country-megamenu, .country-wrap");
            if (!click_box.is(fd.target) && click_box.has(fd.target).length === 0) {
                jQuery('.country-megamenu, .header-top').removeClass("active");
                jQuery('.country-megamenu').slideUp(300);
                jQuery('html').removeClass("country-megaActive");
            }
        });
    } else {
        $('.header-top, .country-megamenu .close, .country-wrap').click(function () {
            $('html').toggleClass('country-megaActive');
            $('.country-megamenu, .header-top').toggleClass('active');
            return false;
        });
        $(document).on('click touchstart', function (fd) {
            var click_box = $(".header-top, .country-megamenu, .country-wrap");
            if (!click_box.is(fd.target) && click_box.has(fd.target).length === 0) {
                jQuery('.country-megamenu, .header-top').removeClass("active");
                jQuery('html').removeClass("country-megaActive");
            }
        });
    }
};

$(document).on('click touchstart', function (fd) {
    var click_box = $(".header-top, .country-megamenu, .country-wrap");
    if (!click_box.is(fd.target) && click_box.has(fd.target).length === 0) {
        jQuery('.country-megamenu, .header-top').removeClass("active");
        jQuery('.country-megamenu').slideUp(300);
        jQuery('html').removeClass("country-megaActive");
    }
});

// Click Dropdown
$(document).on('click touchstart', function (fd) {
    var click_box = $(".search-triger, .search-field");
    if (!click_box.is(fd.target) && click_box.has(fd.target).length === 0) {
        jQuery('.search-triger').removeClass("active");
        jQuery(".search-field").hide();
    }
});

//Sidbar
jQuery('.drop-click').click(function () {
    jQuery(".hidden-content").slideUp(700);
    jQuery(".hidden-form").slideDown(700);
});

//Search
jQuery('.search-triger').click(function () {
    jQuery(this).toggleClass("active");
    jQuery(".search-field").fadeToggle();
});

jQuery('.small-trigger').click(function () {
    jQuery(this).toggleClass("active");
    jQuery(this).next().slideToggle();
});

// Header Shrink
$(function () { function b() { return window.pageYOffset || document.documentElement.scrollTop } $(window).scroll(function () { b() >= 1 ? ($("html").addClass("head-fix")) : ($("html").removeClass("head-fix")) }) });
// Header Shrink End

// On Scroll Class Add
// Background set
$(".bg img").each(function (da) { var db = $(this).attr("src"); $(this).parent().css("background-image", "url(" + db + ")") });

// Scroll Top Js
var offset = 0, duration = 500;
$(window).scroll(function () {
    if ($(this).scrollTop() > offset) {
        $('.scrollup').addClass('in');
    } else {
        $('.scrollup').removeClass('in');
    }
});

$(".scrollup").click(function (a) {
    return a.preventDefault(),
        $("html, body").animate({
            scrollTop: 0
        }, duration),
        !1
});

// class adding in image
$(".alignright,.alignleft").closest("p").addClass("pn")
$("#edit-field-date-of-birth-0-value").removeClass("container-inline")


// Table wrap
$("table").wrap("<div class='table-responsive'></div>");


// Lazy load
$(function (a) { var b = function () { a("[data-image]").each(function () { var b = a(this).offset().top, c = a(window).scrollTop(), d = a(window).height(), e = b - c < d; e && (a(this).attr("src", a(this).attr("data-image")).removeClass("lazy"), a(this).attr("src", a(this).attr("data-src")).addClass("lazyFade")) }) }; a(function () { b(), a(window).scroll(function () { b() }), a(window).on("load", function () { b() }) }) });

// Accordian
$('.accordion:first-of-type').addClass('active');
$('.accordion:first-of-type .accordion-content').slideDown().addClass('active');
$('.accordion-head').click(function () {
    if ($(this).next('.accordion-content').hasClass("active")) {
        $(this).parent().removeClass('active');
        $(this).next('.accordion-content').slideUp().removeClass('active');
    } else {
        $(this).parent().addClass('active');
        $(this).next('.accordion-content').slideDown().addClass('active');
        $(this).parent().siblings('.accordion').removeClass('active');
        $(this).parent().siblings('.accordion').children('.accordion-content').slideUp().removeClass('active');
    }
});


//Show Hide By Anchor
$('ul.custom-tab a').click(function () {
    var $box = $('.tab-pane');
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
            var id = this.hash;
            $("ul.custom-tab a").each(function () {
                if ($(this).attr("href") == id) {
                    // $( ".anchor-click a" ).removeClass("active");
                    $(this).parent().addClass("active");
                } else {
                    $(this).parent().removeClass("active");
                }
            });
            $box.not(id).stop().hide().removeClass("active");
            $(id).fadeIn().addClass("active");
            return false;
        }
    }
});


//All Function

/**
* jquery-match-height master by @liabru
* http://brm.io/jquery-match-height/
* License: MIT
*/
!function (t) { "use strict"; "function" == typeof define && define.amd ? define(["jquery"], t) : "undefined" != typeof module && module.exports ? module.exports = t(require("jquery")) : t(jQuery) }(function (l) { function h(t) { return parseFloat(t) || 0 } function c(t) { var e = l(t), n = null, a = []; return e.each(function () { var t = l(this), e = t.offset().top - h(t.css("margin-top")), o = 0 < a.length ? a[a.length - 1] : null; null === o ? a.push(t) : Math.floor(Math.abs(n - e)) <= 1 ? a[a.length - 1] = o.add(t) : a.push(t), n = e }), a } function p(t) { var e = { byRow: !0, property: "height", target: null, remove: !1 }; return "object" == typeof t ? l.extend(e, t) : ("boolean" == typeof t ? e.byRow = t : "remove" === t && (e.remove = !0), e) } var n = -1, a = -1, u = l.fn.matchHeight = function (t) { var e = p(t); if (e.remove) { var o = this; return this.css(e.property, ""), l.each(u._groups, function (t, e) { e.elements = e.elements.not(o) }), this } return this.length <= 1 && !e.target || (u._groups.push({ elements: this, options: e }), u._apply(this, e)), this }; u.version = "master", u._groups = [], u._throttle = 80, u._maintainScroll = !1, u._beforeUpdate = null, u._afterUpdate = null, u._rows = c, u._parse = h, u._parseOptions = p, u._apply = function (t, e) { var i = p(e), o = l(t), n = [o], a = l(window).scrollTop(), r = l("html").outerHeight(!0), s = o.parents().filter(":hidden"); return s.each(function () { var t = l(this); t.data("style-cache", t.attr("style")) }), s.css("display", "block"), i.byRow && !i.target && (o.each(function () { var t = l(this), e = t.css("display"); "inline-block" !== e && "flex" !== e && "inline-flex" !== e && (e = "block"), t.data("style-cache", t.attr("style")), t.css({ display: e, "padding-top": "0", "padding-bottom": "0", "margin-top": "0", "margin-bottom": "0", "border-top-width": "0", "border-bottom-width": "0", height: "100px", overflow: "hidden" }) }), n = c(o), o.each(function () { var t = l(this); t.attr("style", t.data("style-cache") || "") })), l.each(n, function (t, e) { var o = l(e), a = 0; if (i.target) a = i.target.outerHeight(!1); else { if (i.byRow && o.length <= 1) return void o.css(i.property, ""); o.each(function () { var t = l(this), e = t.attr("style"), o = t.css("display"); "inline-block" !== o && "flex" !== o && "inline-flex" !== o && (o = "block"); var n = { display: o }; n[i.property] = "", t.css(n), t.outerHeight(!1) > a && (a = t.outerHeight(!1)), e ? t.attr("style", e) : t.css("display", "") }) } o.each(function () { var t = l(this), e = 0; i.target && t.is(i.target) || ("border-box" !== t.css("box-sizing") && (e += h(t.css("border-top-width")) + h(t.css("border-bottom-width")), e += h(t.css("padding-top")) + h(t.css("padding-bottom"))), t.css(i.property, a - e + "px")) }) }), s.each(function () { var t = l(this); t.attr("style", t.data("style-cache") || null) }), u._maintainScroll && l(window).scrollTop(a / r * l("html").outerHeight(!0)), this }, u._applyDataApi = function () { var o = {}; l("[data-match-height], [data-mh]").each(function () { var t = l(this), e = t.attr("data-mh") || t.attr("data-match-height"); o[e] = e in o ? o[e].add(t) : t }), l.each(o, function () { this.matchHeight(!0) }) }; function i(t) { u._beforeUpdate && u._beforeUpdate(t, u._groups), l.each(u._groups, function () { u._apply(this.elements, this.options) }), u._afterUpdate && u._afterUpdate(t, u._groups) } u._update = function (t, e) { if (e && "resize" === e.type) { var o = l(window).width(); if (o === n) return; n = o } t ? -1 === a && (a = setTimeout(function () { i(e), a = -1 }, u._throttle)) : i(e) }, l(u._applyDataApi); var t = l.fn.on ? "on" : "bind"; l(window)[t]("load", function (t) { u._update(!0, t) }), l(window)[t]("resize orientationchange", function (t) { u._update(!0, t) }) });
$(function () { $('.eql, .product-box h3, .award-logo').matchHeight({ property: 'height' }); });


/**
* jquery-match-height End
*/
//Devicewidth
function minus_margin() {
    ww = $(window).width();
    cw = $("header .container").outerWidth();
    dcw = ww - cw;
    doww = dcw / 2 + 15;

    jQuery(".padding-right").css('padding-right', doww);
    jQuery(".padding-left").css('padding-left', doww);

    jQuery(".minus-right").css('margin-right', -doww);
    jQuery(".minus-left").css('margin-left', -doww);

}
minus_margin();


// captcha scale
function captcha_scale() {
    var width = $('.g-recaptcha').parent().width();
    if (width < 302) {
        var scale = width / 302;
        $('.g-recaptcha').css('transform', 'scale(' + scale + ')');
        $('.g-recaptcha').css('-webkit-transform', 'scale(' + scale + ')');
        $('.g-recaptcha').css('transform-origin', '0 0');
        $('.g-recaptcha').css('-webkit-transform-origin', '0 0');
    }
}

// Ready Function
//Captcha
captcha_scale();

// Window Load Function
$(window).on("load", function () {
    //Captcha
    captcha_scale();
    jQuery(".banner-wrap").addClass("active");
    countryMegaMenu();

});
// Window Resize Function
$(window).on("resize", function () {
    captcha_scale();
    minus_margin();
    countryMegaMenu();
});

// Smooth scroll
$('.smooth').click(function (event) {
    if (
        location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '')
        && location.hostname == this.hostname
    ) {

        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

        if (target.length) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 94
            }, 1000);
        }
    }
});


$('ul.custom-tab li a').click(function () {
    var $box = $('.tab-pane');
    if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
        if (target.length) {
            var id = this.hash;
            if ($(this).attr("href") == id) {

                $("ul.custom-tab li a").parent().parent().find("li").removeClass("active");
                $(this).parent().addClass("active");
            }
            else {
                $(this).parent().removeClass("active");
            }
            $box.not(id).stop().hide().removeClass("active");
            $(id).fadeIn().addClass("active");
            return false;
        }
    }
});

//Effect Add Jquery
// Transition Delay
for (i = 0; i < $("").length; i++) {
    $("")[i].style.animationDuration = "1." + (i + 1) + "s";
}
//Effect Name
var hinge = $("");
var swing = $("");
var fadeInLeft = $("");
var fadeInRight = $("");
var fadeInUp = $("");
var fadeInDown = $("");
var fadeIn = $(".main-banner img, .google-map iframe, .full-w-banner .bg");
var slideInUp = $("");
var zoomIn = $("");
//Effect Name End

$(function () {
    $(hinge).addClass("animateblock hinge");
    $(swing).addClass("animateblock swing");
    $(fadeInLeft).addClass("animateblock fadeInLeft");
    $(fadeInRight).addClass("animateblock fadeInRight");
    $(fadeInUp).addClass("animateblock fadeInUp");
    $(fadeInDown).addClass("animateblock fadeInDown");
    $(fadeIn).addClass("animateblock fadeIn");
    $(slideInUp).addClass("animateblock slideInUp");
    $(zoomIn).addClass("animateblock zoomIn");

    function getCurrentScroll() {
        return window.pageYOffset || document.documentElement.scrollTop;
    }
    var $elems = $('.animateblock');
    var winheight = $(window).height();
    var fullheight = $(document).height();
    animate_elems();
    $(window).scroll(function () {
        animate_elems();
    });

    function animate_elems() {
        wintop = $(window).scrollTop();
        $elems.each(function () {
            $elm = $(this);
            if ($elm.hasClass('animated')) {
                return true;
            }
            topcoords = $elm.offset().top;
            if (wintop > (topcoords - (winheight * .9))) {
                $elm.addClass('animated');
            }
        });
    }
});