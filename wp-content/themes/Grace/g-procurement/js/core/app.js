/* ------------------------------------------------------------------------------
 *
 *  # Template JS core
 *
 *  Core JS file with default functionality configuration
 *
 *  Version: 1.2
 *  Latest update: Dec 11, 2015
 *
 * ---------------------------------------------------------------------------- */


// Allow CSS transitions when page is loaded
$(window).on('load', function() {
    $('body').removeClass('no-transitions');
});


$(function() {

    // Disable CSS transitions on page load
    $('body').addClass('no-transitions');



    // ========================================
    //
    // Content area height
    //
    // ========================================


    // Calculate min height
    function containerHeight() {
        var availableHeight = $(window).height() - $('.page-container').offset().top - $('.navbar-fixed-bottom').outerHeight();

        $('.page-container').attr('style', 'min-height:' + availableHeight + 'px');
    }

    // Initialize
    containerHeight();




    // ========================================
    //
    // Heading elements
    //
    // ========================================


    // Heading elements toggler
    // -------------------------

    // Add control button toggler to page and panel headers if have heading elements
    $('.panel-heading, .page-header-content, .panel-body, .panel-footer').has('> .heading-elements').append('<a class="heading-elements-toggle"><i class="icon-more"></i></a>');


    // Toggle visible state of heading elements
    $('.heading-elements-toggle').on('click', function() {
        $(this).parent().children('.heading-elements').toggleClass('visible');
    });



    // Breadcrumb elements toggler
    // -------------------------

    // Add control button toggler to breadcrumbs if has elements
    $('.breadcrumb-line').has('.breadcrumb-elements').append('<a class="breadcrumb-elements-toggle"><i class="icon-menu-open"></i></a>');


    // Toggle visible state of breadcrumb elements
    $('.breadcrumb-elements-toggle').on('click', function() {
        $(this).parent().children('.breadcrumb-elements').toggleClass('visible');
    });




    // ========================================
    //
    // Navbar
    //
    // ========================================


    // Navbar navigation
    // -------------------------

    // Prevent dropdown from closing on click
    $(document).on('click', '.dropdown-content', function(e) {
        e.stopPropagation();
    });

    // Disabled links
    $('.navbar-nav .disabled a').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
    });

    // Show tabs inside dropdowns
    $('.dropdown-content a[data-toggle="tab"]').on('click', function(e) {
        $(this).tab('show');
    });




    // ========================================
    //
    // Element controls
    //
    // ========================================


    // Reload elements
    // -------------------------

    // Panels
    $('.panel [data-action=reload]').click(function(e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent().parent();
        $(block).block({
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });

        // For demo purposes
        window.setTimeout(function() {
            $(block).unblock();
        }, 2000);
    });


    // Sidebar categories
    $('.category-title [data-action=reload]').click(function(e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent();
        $(block).block({
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#000',
                opacity: 0.5,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #000'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none',
                color: '#fff'
            }
        });

        // For demo purposes
        window.setTimeout(function() {
            $(block).unblock();
        }, 2000);
    });


    // Light sidebar categories
    $('.sidebar-default .category-title [data-action=reload]').click(function(e) {
        e.preventDefault();
        var block = $(this).parent().parent().parent().parent();
        $(block).block({
            message: '<i class="icon-spinner2 spinner"></i>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait',
                'box-shadow': '0 0 0 1px #ddd'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'none'
            }
        });

        // For demo purposes
        window.setTimeout(function() {
            $(block).unblock();
        }, 2000);
    });



    // Collapse elements
    // -------------------------

    //
    // Sidebar categories
    //

    // Hide if collapsed by default
    $('.category-collapsed').children('.category-content').hide();


    // Rotate icon if collapsed by default
    $('.category-collapsed').find('[data-action=collapse]').addClass('rotate-180');


    // Collapse on click
    $('.category-title [data-action=collapse]').click(function(e) {
        e.preventDefault();
        var $categoryCollapse = $(this).parent().parent().parent().nextAll();
        $(this).parents('.category-title').toggleClass('category-collapsed');
        $(this).toggleClass('rotate-180');

        containerHeight(); // adjust page height

        $categoryCollapse.slideToggle(150);
    });


    //
    // Panels
    //

    // Hide if collapsed by default
    $('.panel-collapsed').children('.panel-heading').nextAll().hide();


    // Rotate icon if collapsed by default
    $('.panel-collapsed').find('[data-action=collapse]').addClass('rotate-180');


    // Collapse on click
    $('.panel [data-action=collapse]').click(function(e) {
        e.preventDefault();
        var $panelCollapse = $(this).parent().parent().parent().parent().nextAll();
        $(this).parents('.panel').toggleClass('panel-collapsed');
        $(this).toggleClass('rotate-180');

        containerHeight(); // recalculate page height

        $panelCollapse.slideToggle(150);
    });



    // Remove elements
    // -------------------------

    // Panels
    $('.panel [data-action=close]').click(function(e) {
        e.preventDefault();
        var $panelClose = $(this).parent().parent().parent().parent().parent();

        containerHeight(); // recalculate page height

        $panelClose.slideUp(150, function() {
            $(this).remove();
        });
    });


    // Sidebar categories
    $('.category-title [data-action=close]').click(function(e) {
        e.preventDefault();
        var $categoryClose = $(this).parent().parent().parent().parent();

        containerHeight(); // recalculate page height

        $categoryClose.slideUp(150, function() {
            $(this).remove();
        });
    });




    // ========================================
    //
    // Main navigation
    //
    // ========================================


    // Main navigation
    // -------------------------

    // Add 'active' class to parent list item in all levels
    $('.navigation').find('li.active').parents('li').addClass('active');

    // Hide all nested lists
    $('.navigation').find('li').not('.active, .category-title').has('ul').children('ul').addClass('hidden-ul');

    // Highlight children links
    $('.navigation').find('li').has('ul').children('a').addClass('has-ul');

    // Add active state to all dropdown parent levels
    $('.dropdown-menu:not(.dropdown-content), .dropdown-menu:not(.dropdown-content) .dropdown-submenu').has('li.active').addClass('active').parents('.navbar-nav .dropdown:not(.language-switch), .navbar-nav .dropup:not(.language-switch)').addClass('active');



    // Main navigation tooltips positioning
    // -------------------------

    // Left sidebar
    $('.navigation-main > .navigation-header > i').tooltip({
        placement: 'right',
        container: 'body'
    });



    // Collapsible functionality
    // -------------------------

    // Main navigation
    $('.navigation-main').find('li').has('ul').children('a').on('click', function(e) {
        e.preventDefault();

        // Collapsible
        $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).toggleClass('active').children('ul').slideToggle(250);

        // Accordion
        if ($('.navigation-main').hasClass('navigation-accordion')) {
            $(this).parent('li').not('.disabled').not($('.sidebar-xs').not('.sidebar-xs-indicator').find('.navigation-main').children('li')).siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(250);
        }
    });


    // Alternate navigation
    $('.navigation-alt').find('li').has('ul').children('a').on('click', function(e) {
        e.preventDefault();

        // Collapsible
        $(this).parent('li').not('.disabled').toggleClass('active').children('ul').slideToggle(200);

        // Accordion
        if ($('.navigation-alt').hasClass('navigation-accordion')) {
            $(this).parent('li').not('.disabled').siblings(':has(.has-ul)').removeClass('active').children('ul').slideUp(200);
        }
    });




    // ========================================
    //
    // Sidebars
    //
    // ========================================


    // Mini sidebar
    // -------------------------

    // Toggle mini sidebar
    $('.sidebar-main-toggle').on('click', function(e) {
        e.preventDefault();

        // Toggle min sidebar class
        $('body').toggleClass('sidebar-xs');
    });



    // Sidebar controls
    // -------------------------

    // Disable click in disabled navigation items
    $(document).on('click', '.navigation .disabled a', function(e) {
        e.preventDefault();
    });


    // Adjust page height on sidebar control button click
    $(document).on('click', '.sidebar-control', function(e) {
        containerHeight();
    });


    // Hide main sidebar in Dual Sidebar
    $(document).on('click', '.sidebar-main-hide', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-main-hidden');
    });


    // Toggle second sidebar in Dual Sidebar
    $(document).on('click', '.sidebar-secondary-hide', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-secondary-hidden');
    });


    // Hide detached sidebar
    $(document).on('click', '.sidebar-detached-hide', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-detached-hidden');
    });


    // Hide all sidebars
    $(document).on('click', '.sidebar-all-hide', function(e) {
        e.preventDefault();

        $('body').toggleClass('sidebar-all-hidden');
    });



    //
    // Opposite sidebar
    //

    // Collapse main sidebar if opposite sidebar is visible
    $(document).on('click', '.sidebar-opposite-toggle', function(e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');

        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Make main sidebar mini
            $('body').addClass('sidebar-xs');

            // Hide children lists
            $('.navigation-main').children('li').children('ul').css('display', '');
        } else {

            // Make main sidebar default
            $('body').removeClass('sidebar-xs');
        }
    });


    // Hide main sidebar if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-main-hide', function(e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');

        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Hide main sidebar
            $('body').addClass('sidebar-main-hidden');
        } else {

            // Show main sidebar
            $('body').removeClass('sidebar-main-hidden');
        }
    });


    // Hide secondary sidebar if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-secondary-hide', function(e) {
        e.preventDefault();

        // Opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');

        // If visible
        if ($('body').hasClass('sidebar-opposite-visible')) {

            // Hide secondary
            $('body').addClass('sidebar-secondary-hidden');

        } else {

            // Show secondary
            $('body').removeClass('sidebar-secondary-hidden');
        }
    });


    // Hide all sidebars if opposite sidebar is shown
    $(document).on('click', '.sidebar-opposite-hide', function(e) {
        e.preventDefault();

        // Toggle sidebars visibility
        $('body').toggleClass('sidebar-all-hidden');

        // If hidden
        if ($('body').hasClass('sidebar-all-hidden')) {

            // Show opposite
            $('body').addClass('sidebar-opposite-visible');

            // Hide children lists
            $('.navigation-main').children('li').children('ul').css('display', '');
        } else {

            // Hide opposite
            $('body').removeClass('sidebar-opposite-visible');
        }
    });


    // Keep the width of the main sidebar if opposite sidebar is visible
    $(document).on('click', '.sidebar-opposite-fix', function(e) {
        e.preventDefault();

        // Toggle opposite sidebar visibility
        $('body').toggleClass('sidebar-opposite-visible');
    });



    // Mobile sidebar controls
    // -------------------------

    // Toggle main sidebar
    $('.sidebar-mobile-main-toggle').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-main').removeClass('sidebar-mobile-secondary sidebar-mobile-opposite sidebar-mobile-detached');
    });


    // Toggle secondary sidebar
    $('.sidebar-mobile-secondary-toggle').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-secondary').removeClass('sidebar-mobile-main sidebar-mobile-opposite sidebar-mobile-detached');
    });


    // Toggle opposite sidebar
    $('.sidebar-mobile-opposite-toggle').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-opposite').removeClass('sidebar-mobile-main sidebar-mobile-secondary sidebar-mobile-detached');
    });


    // Toggle detached sidebar
    $('.sidebar-mobile-detached-toggle').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-mobile-detached').removeClass('sidebar-mobile-main sidebar-mobile-secondary sidebar-mobile-opposite');
    });



    // Mobile sidebar setup
    // -------------------------

    $(window).on('resize', function() {
        setTimeout(function() {
            containerHeight();

            if ($(window).width() <= 768) {

                // Add mini sidebar indicator
                $('body').addClass('sidebar-xs-indicator');

                // Place right sidebar before content
                $('.sidebar-opposite').insertBefore('.content-wrapper');

                // Place detached sidebar before content
                $('.sidebar-detached').insertBefore('.content-wrapper');
            } else {

                // Remove mini sidebar indicator
                $('body').removeClass('sidebar-xs-indicator');

                // Revert back right sidebar
                $('.sidebar-opposite').insertAfter('.content-wrapper');

                // Remove all mobile sidebar classes
                $('body').removeClass('sidebar-mobile-main sidebar-mobile-secondary sidebar-mobile-detached sidebar-mobile-opposite');

                // Revert left detached position
                if ($('body').hasClass('has-detached-left')) {
                    $('.sidebar-detached').insertBefore('.container-detached');
                }

                // Revert right detached position
                else if ($('body').hasClass('has-detached-right')) {
                    $('.sidebar-detached').insertAfter('.container-detached');
                }
            }
        }, 100);
    }).resize();




    // ========================================
    //
    // Other code
    //
    // ========================================


    // Plugins
    // -------------------------

    // Popover
    $('[data-popup="popover"]').popover();


    // Tooltip
    $('[data-popup="tooltip"]').tooltip();


    // Callback(function() {
    // console.log(GetShowMenuList_yb());
    // });
});

function Callback(callback) {
    callback();
}

function GetShowMenuList_yb() {
    var jgId;
    var menulist = window.sessionStorage.getItem('menulist');
    if (menulist) {
        // jgId = ShowMenulist_yb(menulist);
        // return jgId;
        Callback(function() {
            jgId = ShowMenulist_yb(menulist);
        });
        return jgId;
    } else {
        $.getJSON(getRootDir() + 'index.php/RestYbUserManage/GetAllMenuList?token=' + getUrlParam('token'), function(json) {
            if (json.resp.err == 0) {
                menulist = json.content;
                if (menulist) {
                    window.sessionStorage.removeItem('menulist');
                    window.sessionStorage.setItem('menulist', JSON.stringify(menulist));
                    // jgId = ShowMenulist_yb(menulist);
                    // return jgId;
                    Callback(function() {
                        jgId = ShowMenulist_yb(menulist);
                    });

                    return jgId;
                }
            }
        });
    }
    // console.log(jgId);
}

function ShowMenulist_yb(menulist) {
    var jgId;
    if (typeof menulist == 'string') {
        menulist = JSON.parse(menulist);
    }
    if (menulist.level6 != undefined && menulist.level6.length > 0) {
        Callback(function() {
            jgId = showLevel('讲师', 'Public/youbei/images/4.png', '#menu_lecturer', menulist.level6, menulist.jglist_level6, '6');
        });
    }
    if (menulist.level45 != undefined && menulist.level45.length > 0) {
        Callback(function() {
            jgId = showLevel('馆长', 'Public/youbei/images/3.png', '#menu_curator', menulist.level45, menulist.jglist_level4_5, '4');
        });
    }
    if (menulist.level23 != undefined && menulist.level23.length > 0) {
        Callback(function() {
            jgId = showLevel('城市授权商', 'Public/youbei/images/2.png', '#menu_city', menulist.level23, menulist.jglist_level2_3, '2');
        });
    }
    if (menulist.level1 != undefined && menulist.level1.length > 0) {
        Callback(function() {
            jgId = showLevel('总部', 'Public/youbei/images/1.png', '#menu_hq', menulist.level1, menulist.jglist_level1, '1');
        });
    }
    return jgId;
}

function showLevel(name, src, id, level, jgLevel, levId) {
    // jgLevel.unshift({ 'jgid': '0', 'level': levId, 'title': '全部' });
    var menuActive = window.sessionStorage.getItem('menuActive');
    if (menuActive) {
        menuActive = JSON.parse(menuActive);
    } else {
        menuActive = {
            'name': name,
            'src': src,
            'id': id,
            'level': level,
            'jgLevel': jgLevel
        };
    }
    $('#userTypeName').text(menuActive.name);
    $('#userTypeImg').attr('src', getRootDir() + menuActive.src);
    $('#dropdownMenu1').data('level', menuActive.level);
    $('#dropdownMenu2').data('jgLevel', menuActive.jgLevel);
    $('#level_select').removeClass('hidden');
    $(id).removeClass('hidden');
    $(id).data('level', level);
    $(id).data('jgLevel', jgLevel);
    $(id).data('levId', levId);

    Callback(function() {
        showSidebar($('#dropdownMenu1').data('level'), true);
    });
    // Callback(function() {
    //    var jgId =  showJgListLevel(menuActive.jgLevel);
    //     return jgId;
    // });
    return showJgListLevel(menuActive.jgLevel);
    // console.log(showJgListLevel(menuActive.jgLevel));
}

function showSidebar(level, type) {
    if (level != undefined) {
        for (var i = 0; i < level.length; i++) {
            if (level[i].checked == '1') {
                if (type) {
                    $('#' + level[i].menuid).removeClass('hidden');
                } else {
                    $('#' + level[i].menuid).addClass('hidden');
                }

            }
        }
    }
}

function changeLevel(th) {
    var menuActive = {
        'name': $(th).children('a').text(),
        'src': $(th).find('img').attr('src'),
        'id': $(th).attr('id'),
        'level': $(th).data('level'),
        'jgLevel': $(th).data('jgLevel'),
        'levId': $(th).data('levId')
    };
    var oldMenuActive = window.sessionStorage.getItem('menuActive');
    if (oldMenuActive) {
        Callback(function() {
            showSidebar(JSON.parse(oldMenuActive).level, false);
        });

    }
    window.sessionStorage.removeItem('jgLevelActive');
    // window.sessionStorage.removeItem('menulist');
    window.sessionStorage.setItem('menuActive', JSON.stringify(menuActive));
    Callback(function() {
        showLevel(
            $(th).children('a').text(),
            $(th).find('img').attr('src'),
            $(th).attr('id'),
            $(th).data('level'),
            $(th).data('jgLevel'),
            $(th).data('levId')
        );
    });

    window.location.href = $('#sidebarContent li:visible:first li a').attr('href');
    // window.location.reload();
}

function showJgListLevel(jgList) {
    if (jgList != undefined && jgList.length > 0) {
        var jgHtml = '';
        $('#jgListLevel').html(jgHtml);
        var i = 0;
        var j = 0;
        for (i in jgList) {
            jgHtml += '<li onclick="changeJgLevel(this)"><a href="#">' + jgList[i].title + '</a></li>';
            // $('#jgListLevel').html(jgHtml);
            // $('#jgListLevel  li').eq(i).data('jgLevelObj', jgList[i]);
        }
        $('#jgListLevel').html(jgHtml);
        for (j in $('#jgListLevel > li')) {
            $('#jgListLevel li').eq(j).data('jgLevelObj', jgList[j]);
        }
        $('#jgTypeName').text(jgList[0].title);
        $('#dropdownMenu2').data('jgLevel', jgList[0]);
        // alert($('#dropdownMenu2').data('jgLevel'));
    }
    var jgLevelActive = window.sessionStorage.getItem('jgLevelActive');
    if (jgLevelActive) {
        jgLevelActive = JSON.parse(jgLevelActive);
        $('#jgTypeName').text(jgLevelActive.title);
        $('#dropdownMenu2').data('jgLevel', jgLevelActive);
    }
    var jgId = $('#dropdownMenu2').data('jgLevel');
    return { 'jgList': jgList, 'activeJg': jgId };
}

function changeJgLevel(th) {
    $('#dropdownMenu2').data('jgLevel', $(th).data('jgLevelObj'));
    $('#jgTypeName').text($(th).data('jgLevelObj').title);
    window.sessionStorage.removeItem('jgLevelActive');
    window.sessionStorage.setItem('jgLevelActive', JSON.stringify($(th).data('jgLevelObj')));
    window.location.reload();
}

// function jgId(fun) {
//     // console.log(fun);
//     alert('2');
//     // var jgId =  $('#dropdownMenu2').data('jgLevel').jgid;
//     // return jgId;
// }