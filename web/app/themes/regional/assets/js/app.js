(function ($) {
    var controller = new ScrollMagic.Controller();

    /**
     * Opsleb content tabs
     */
    var Tabs = {
        tab:'.tab',
        currentTab : 0,
        init:function () {
            console.log(this);
        },
        get:function () {
            return this.currentTab;
        },
        set:function (new_value) {
            this.currentTab = new_value;
        },
        render:function () {
            // Render content
            jQuery('.' + this.currentTab).css({
                display:'block'
            }).siblings().css({
                display:'none'
            })

            // Change tab style
            jQuery('.tab[data-tab=' + this.currentTab + ']').addClass('actif').siblings().removeClass('actif')
        }
    }

    var homeCarousel = {
        options : {
            loop:true,
            speed: 500,
            autoplay:false,
            pagination: {
                el: ".swiper-pagination",
                clickable: true
            }
        }
    }
    var factSheet = {
        options : {
            loop:false,
            speed:500,
            autoplay:false,
            spaceBetween: 30,
            slidesPerView:1,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev'
            },
            breakpoints : {
                780: {
                    slidesPerView: 2
                },
                1000: {
                    slidesPerView: 3
                },
                1279: {
                    slidesPerView: 4
                },
                1440: {
                    slidesPerView: 5
                }
            }
        }
    }

    var project = new Swiper('.projectCarousel', {
        loop:false,
        speed:500,
        autoplay:false,
        spaceBetween: 30,
        slidesPerView:1,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        breakpoints : {
            780: {
                slidesPerView: 2
            },
            1000: {
                slidesPerView: 'auto'
            },
            1279: {
                slidesPerView: 'auto'
            },
            1300: {
                slidesPerView: 3
            },
            1500: {
                slidesPerView: 'auto'
            }
            ,
            1500: {
                slidesPerView: 4
            },
            2000: {
                slidesPerView: 5
            }
        }
    })



    var arrowHopeStoriesOptions = {
        element: '.rounded-full',
        trigger: '#hope--stories',
        hook: 'onEnter',
        offset:300,
    }


    animateElement(arrowHopeStoriesOptions);

    // Home swiper
    var swiper = new Swiper('.homeSlider', homeCarousel.options);
    var activities = new Swiper('.activitiesSlider', {
        slidesPerView: 1,
        autoHeight: false,
        spaceBetween:15,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev'
        },
        breakpoints : {
            780: {
                slidesPerView: 2
            },
            1200: {
                slidesPerView: 3
            }
        }
    })
    var FactSheetSwiper = new Swiper('.factsheetSlider', factSheet.options);

    // Render tabs
    jQuery('.tab').on('click', function () {
        var t = jQuery(this).attr('data-tab')
        Tabs.set(t);
        Tabs.render();
    })

    function animateFigures()
    {
        var figures = jQuery('figure');
        jQuery.each(figures, function (key, item) {
            var s = jQuery(item).find('span')
            var tl = TweenMax.to(s, 1, {
                width: 0,
                ease: Power4.easeOut
            });
            var f = new ScrollMagic.Scene({
                triggerElement: item,
                triggerHook: item,
                offset: 0
            }).setTween(tl).addTo(controller)
        });

        // Animate books
        var books = jQuery('.books');

        var tb = TweenMax.staggerTo('.books', 1, {y:-200, stagger:0.2});

        var b = new ScrollMagic.Scene({
            triggerElement: "#books-container",
            triggerHook: "#books-container",
            offset: 0
        }).setTween(tb).addTo(controller)
    }

    animateFigures()

    /**
     * Animate home hope stories arrow
     */
    function animateElement(options)
    {
        var tl = TweenMax.to(options.element, 1.25, {
            scale: 1.2,
            ease: Elastic.easeOut
        });
        var scene = new ScrollMagic.Scene({
            triggerElement: options.trigger,
            triggerHook: options.hook,
            offset: options.offset
        })
            .setTween(tl)
            .addTo(controller);
    }

    function headingStyle()
    {
        if (document.querySelector('.page')) {
            var page = document.querySelector('.page')
            var hs   = page.querySelectorAll('h2');
            if (hs.length) {
                var l = hs.length;
                for (var i=0; i < l; i++) {
                    var elem = hs[i].querySelector('strong');

                    if (elem) {
                        var separator = document.createElement('div');

                        // The heading element
                        var elem_rect = elem.getBoundingClientRect();
                        var elem_width = elem_rect.width;
                        separator.setAttribute('id', 'h2-'+ i);
                        separator.style.width = Math.round(elem_rect.left + (elem_width/2)) + 'px';
                        separator.style.height = '5px';
                        separator.classList.add('bg-blue');
                        separator.classList.add('mt-5');
                        separator.style.transform = 'translate(-'+ elem_rect.left +'px, 0)'
                        elem.appendChild(separator);
                    }
                }
            }
        }

    }

    /**
     * This function handles accordion template
     */
    function hopesAccordion()
    {
        var accordion = jQuery('.accordion-item');
        jQuery.each(accordion, function () {
            var item = jQuery(this);
            var content = item.find('.accordion-content');
            item.find('h4').on('mouseenter', function () {
                content.removeClass('hidden');
                item.siblings().find('.accordion-content').addClass('hidden');
            })
        })
    }

    /**
     * Create video player
     */
    function videoPlayer(videoID, videoUrl, infosurl, infostitle, infostarget)
    {
        // craete video container element
        var container = document.createElement('div');
        // crate the player itself
        var player = document.createElement('video');
        // create player source tag
        var sourceMP4 = document.createElement("source");
        sourceMP4.type = "video/mp4";
        sourceMP4.src = videoUrl;
        // add source to player
        player.append(sourceMP4);
        player.setAttribute('id', 'video-' + videoID);
        player.setAttribute("controls", "controls");
        player.classList.add('mb-5')
        player.play();
        // add player to container
        container.classList.add('video-player-container');
        container.appendChild(player);
        // add more infos btn
        container.appendChild(videoMoreInfos(infosurl, infostitle, infostarget))

        // return the container
        return container;

    }

    function youtubePlayer(videoID, videoUrl, infosurl, infostitle, infostarget)
    {
        console.log(videoUrl)
        var player = "<iframe class=\"mb-5\" width=\"560\" height=\"315\" src='"+videoUrl+"' frameborder=\"0\" allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" allowfullscreen></iframe>";
        var container = document.createElement('div');
        container.classList.add('video-player-container');
        container.insertAdjacentHTML('beforeend', player);
        container.appendChild(videoMoreInfos(infosurl, infostitle, infostarget))
        return container;
    }

    /**
     * Remove video from DOM
     */
    function removeVideo()
    {
        if (document.querySelector('.video-player-container')) {
            var v = document.querySelector('.video-player-container');
            v.parentNode.removeChild(v);
        }
        document.querySelector('.overlay').classList.remove('view');
    }

    /**
     * Add video to overlay
     * @param container
     */
    function addVideo(container)
    {
        var overlay = document.querySelector('.overlay');
            overlay.classList.add('view');
            overlay.appendChild(container);
    }

    /**
     * More infos button url
     * @param more_infos_url
     */
    function videoMoreInfos(url="#", title="More infos", target="_self")
    {

        var a = document.createElement('a');
        var d = document.createElement('div');
        d.classList.add('text-right');
        a.setAttribute('href', url);
        a.setAttribute('role', 'button');
        a.setAttribute('target', target)
        a.classList.add('btn','btn-fill','bg-blue-dark', 'text-white', 'uppercase', 'text-small', 'py-4', 'px-10');
        a.innerHTML = title;
        d.appendChild(a)
        return d
    }

    /**
     * Open video
     */
    function openVideo()
    {

        if (document.querySelector('.open-video')) {
            var buttons = document.querySelectorAll('.open-video');
            for (let i = 0; i < buttons.length; i++) {
                var button = buttons[i];
                button.addEventListener('click', function () {
                    removeVideo();
                    var videoID = Date.now();
                    var videoUrl = this.getAttribute('data-url');
                    var videoExtern = this.getAttribute('data-videourl');
                    var infosurl = this.getAttribute('data-infosurl');
                    var infostitle = this.getAttribute('data-infostitle');
                    var infostarget = this.getAttribute('data-infostarget');

                    if (videoExtern != "") {
                        var container = youtubePlayer(videoID, videoExtern, infosurl, infostitle, infostarget);
                    } else if (videoUrl) {
                        var container = videoPlayer(videoID, videoUrl, infosurl, infostitle, infostarget);
                    }

                    addVideo(container)
                })
            }
        }
    }

    /**
     * Close video
     */
    function closeVideo()
    {
        document.querySelector('.close-video').addEventListener('click', function () {
            removeVideo();
        })
    }

    /* Dropdown menu */
    function dropDownMenu()
    {
        /*Dropdown Menu*/
        jQuery('.dropdown').click(function () {
            jQuery(this).attr('tabindex', 1).focus();
            jQuery(this).toggleClass('active');
            jQuery(this).find('.dropdown-menu').slideToggle(300);
        });
        jQuery('.dropdown').focusout(function () {
            jQuery(this).removeClass('active');
            jQuery(this).find('.dropdown-menu').slideUp(300);
        });
        jQuery('.dropdown .dropdown-menu li').click(function () {
            jQuery(this).parents('.dropdown').find('span').text(jQuery(this).text());
            jQuery(this).parents('.dropdown').find('input').attr('value', jQuery(this).attr('id'));
        });
        /*End Dropdown Menu*/


        jQuery('.dropdown-menu li').click(function () {
            var input = jQuery(this).parents('.dropdown').find('input').val();
            console.log(input);
        });
    }

    /**
     * Filter scholarship by terms
     */
    function scholarshipTerms()
    {
        var filter = jQuery('.filter-by-terms');
        filter.find('a').on('click', function (event) {
            event.preventDefault();
            var term = jQuery(this).attr('data-term');
            var postitem = jQuery('.post-item');
            var l = postitem.filter('.' + term).length;
            jQuery('.no-posts').remove();
            jQuery(this).addClass('text-blue-light').parent('li').siblings().find('a').removeClass('text-blue-light')
            postitem.addClass('hidden').filter('.' + term).removeClass('hidden');
            if (l === 0) {
                jQuery('.posts-list').append('' +
                    '<div class="no-posts p-10 text-center w-full">' +
                    '   <div class="bg-teal-100 border-t-4 border-blue text-teal-900 px-4 py-3 border-solid border" role="alert">\n' +
                    '    <p class="text-sm text-center">No opportunities available </p>\n' +
                    '</div></div>');
            }
        })
    }
    function openMenu()
    {
        //CSSPlugin.defaultTransformPerspective = 600;
        var toggleMenu = $('.menu-toggle');
        var listItems = $('ul#menu li a');
        var menuContainer = $('.menu-container');
        var timeline = new TimelineMax({ paused: true, reversed: true });
        var screen_height = window.innerHeight;
        timeline.staggerTo(
            menuContainer,
            .50,
            {height:screen_height, opacity:1,  immediateRender:false, onComplete:function () {
                console.log('completed')}}
        );

        timeline.staggerFromTo(
            listItems,
            .25,
            { autoAlpha: 0, top: 50  },
            { autoAlpha: 1, top: 0, ease: "Expo.easeOut" },
            0.1
        );

        timeline.staggerTo('.croix-item', 1, {y:10, opacity:1, stagger:0.1});

        // timeline.staggerFromTo(listItems, 1.2, { autoAlpha: 0, rotationX: -90, transformOrigin: '50% 0%' }, { autoAlpha: 1, rotationX: 0, ease: Elastic.easeOut.config(1, 0.3) }, 0.1, 0.3);
        //timeline.to(menuContainer, 0.5, { height:1000})
        toggleMenu.on('click', function () {
            //menuContainer.toggleClass('on');
            if (timeline.reversed()) {
                timeline.play();
            } else {
                timeline.reverse();
            }
        });
    }

    /* Language switcher */
    if (document.getElementById("language-switcher")) {
        window.onscroll = function () {
            makeFixed()};
        var ls = document.getElementById("language-switcher");
        // Get the offset position of the navbar
        var sticky = ls.offsetTop;
    }

    // Add the sticky class to the header
    // when you reach its scroll position.
    // Remove "sticky" when you leave the scroll position
    function makeFixed()
    {
        if (window.pageYOffset > sticky) {
            ls.classList.add("sticky");
        } else {
            ls.classList.remove("sticky");
        }
    }

    /**
     * Download files
     * */
    function downloadFiles()
    {
        if (jQuery('.download').length) {
            console.log('yes')
            jQuery('.download').each(function () {

                var item = jQuery(this);
                item.on('click', function () {
                    var uri = item.attr('data-link');
                    var link = document.createElement('a');
                    link.href = uri;
                    link.setAttribute('target', '_blank');
                    document.body.appendChild(link);
                    link.click();
                    link.remove();
                })
            })
        }

        // Open all external links to new window
        jQuery(document).on('click', 'a[href$=".pdf"]', function () {
            $(this).attr('target', "_blank");
        });
        // Open all urls that don't belong to our domain in a new window or tab
        jQuery(document).on('click', "a[href^='http:']:not([href*='" + window.location.host + "'])", function () {
            $(this).attr("target", "_blank");
        });
    }

    function closePopUp()
    {
        const close = $(".close");
        const toggleNewsletter = $('.toggle-newsletter');
        var emailContainer = $('.sendingblue-form');
        var timeline = new TimelineMax({ paused: true, reversed: true });
        var screen_height = window.innerHeight;

        timeline.staggerTo(
            emailContainer,
            .50,
            {height:screen_height, opacity:1,  immediateRender:true, onComplete:function () {
                    console.log('completed')}}
        );
        timeline.staggerTo(close, .5, {y:10, opacity:1, stagger:0.1});
        toggleNewsletter.on("click", function (event) {
            event.preventDefault();
            if (timeline.reversed()) {
                timeline.play();
            } else {
                timeline.reverse();
            }
        })
    }

    function focus_field() {
        var field = jQuery('input:not(.select)');
        field.on('focus', function(){
            jQuery(this).parent('span').siblings('label').css({
                'top' : "-10px"
            })
        })

        field.on('blur', function(){

            var top = 0;

            if(jQuery(this).val() !== "") {
                 top = "-10px";
            }

            jQuery(this).parent('span').siblings('label').css({
                'top' : top
            })

        })
    }

    function forgot_password(){
        var fp = jQuery('.forgot-password');
        var ff = jQuery('.forgot-field');
        var lf = jQuery('.login-form');

        fp.on('click', function(){
            ff.removeClass('hidden');
            lf.addClass('hidden');
            jQuery('div[role="alert"]').addClass('hidden')
            jQuery('div[name="login-user"]').val(2)
            console.log(jQuery('div[name="login-user"]'))
        })
    }

    forgot_password()
    focus_field()
    closePopUp();
    downloadFiles()
    openMenu();
    scholarshipTerms();
    dropDownMenu();
    openVideo();
    closeVideo();
    headingStyle();
    hopesAccordion();

})(jQuery)