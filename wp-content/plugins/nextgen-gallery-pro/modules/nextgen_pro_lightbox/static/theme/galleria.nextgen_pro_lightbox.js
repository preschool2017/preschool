(function($) {

    // In case this theme is enqueued before Galleria.js is loaded we
    // will periodically check if Galleria exists before calling
    // Galleria.addTheme()
    var interval = setInterval(function() {
        if (typeof window.Galleria !== 'undefined') {
            clearInterval(interval);
            add_nggpl_theme();
        }
    }, 10);

    var add_nggpl_theme = function() {
        Galleria.addTheme({
            name: 'nextgen_pro_lightbox',
            author: 'Imagely',
            version: 2.0,
            defaults: {
                debug: false,
                responsive: true,
                carousel: true,
                thumbnails: true,
                fullscreen: false,
                trueFullscreen: false,
                fullscreenDoubleTap: false,
                maxScaleRatio: 1
            },
            init: function(options) {
                var self = this;

                Galleria.requires(1.41, 'This version of the NextGEN Pro Lightbox theme requires Galleria 1.4.1 or later');

                // Some objects have aip variables - animation-in-progress - and are used to make jQuery animations smoother
                var methods = {
                    sidebar: {
                        _is_open: false,
                        _type: '',
                        is_open: function(state) {
                            if (typeof state != 'undefined') {
                                this._is_open = state;
                            } else {
                                return this._is_open;
                            }
                        },
                        toggle: function(type) {
                            if (this.is_open() && type == this.get_type()) {
                                this.close();
                            } else {
                                this.open(type);
                            }
                        },
                        open: function(type) {
                            self.$('sidebar-overlay').addClass('npl-sidebar-overlay-open');
                            self.$('container').addClass('npl-sidebar-open');
                            $('#npl_button_close').addClass('npl-sidebar-open');

                            this.render(type);
                            methods.sidebar.is_open(true);

                            var state = $.nplModal('get_state');
                            state.sidebar = type;

                            $.nplModal(
                                'router.navigate',
                                $.nplModal('get_setting', 'router_slug')
                                + '/' + state.slug
                                + '/' + state.image_id
                                + '/' + type,
                                true
                            );

                            $(window).trigger('resize');
                            self.trigger('npl.sidebar.opened');
                        },
                        close: function() {
                            self.$('sidebar-overlay').removeClass('npl-sidebar-overlay-open');
                            self.$('container').removeClass('npl-sidebar-open');
                            $('#npl_button_close').removeClass('npl-sidebar-open');

                            methods.sidebar.is_open(false);

                            var state = $.nplModal('get_state');
                            state.sidebar = null;
                            $.nplModal(
                                'router.navigate',
                                $.nplModal('get_setting', 'router_slug')
                                + '/' + state.slug
                                + '/' + state.image_id,
                                true
                            );

                            $(window).trigger('resize');
                            self.trigger('npl.sidebar.closed');
                        },
                        render: function(type) {
                            // switching to another sidebar type; flash the overlay
                            if (type != this.get_type()) {
                                this._type = type;
                                $.nplModal('get_state').sidebar = type;
                            }
                            methods.sidebars[type].render(methods.galleria.get_current_image_id());
                            methods.sidebars[type].init();
                        },
                        get_type: function() {
                            return this._type;
                        },
                        events: {
                            bind: function() {
                                self.bind('npl.init', this.npl_init);
                                self.bind('npl.init.complete', this.npl_init_complete);
                                self.bind('npl.sidebar.rendered', this.rendered);
                            },
                            npl_init: function() {
                                // Add sidebar container & overlay
                                self.addElement('sidebar-container');
                                self.addElement('sidebar-overlay');
                                self.addElement('sidebar-spinner');
                                self.addElement('sidebar-toggle');
                                self.$('sidebar-container').css({background: $.nplModal('get_setting', 'sidebar_background_color')});
                                self.$('sidebar-toggle').css({color: methods.icons.get_overlay_color()});

                                // adds the spinning 'loading' animation
                                var sidebar_spinner = $('<i/>').addClass('fa fa-spin fa-spinner');
                                $(self._dom.stage).append(sidebar_spinner);
                                self.append({'sidebar-spinner': sidebar_spinner});

                                var sidebar_toggle = $('<i/>')
                                    .addClass('fa fa-arrow-circle-right')
                                    .click(function(event) {
                                        event.preventDefault();
                                        methods.sidebar.close();
                                    });
                                $(self._dom.stage).append(sidebar_toggle);
                                self.append({'sidebar-toggle': sidebar_toggle});
                            },
                            npl_init_complete: function() {
                                self.appendChild('container', 'sidebar-container');
                                self.appendChild('container', 'sidebar-overlay');
                                self.appendChild('sidebar-overlay', 'sidebar-spinner');
                                self.appendChild('container', 'sidebar-toggle');
                            },
                            rendered: function() {
                                // This is hidden except for narrow viewports by default, allows phone users to remember what photo they are 'on'
                                $('#nggpl-sidebar-thumbnail-img').attr('src', self.getData(self.getIndex()).image);
                            }
                        }
                    },
                    sidebars: {
                        comments: {
                            _cache: [],
                            // because the .length operator isn't accurate
                            get_cache_size: function() {
                                return $.map(this._cache, function(n, i) { return n; }).length
                            },
                            // returns the image-id field of the first preceeding image found whose comments aren't cached
                            get_prev_uncached_image_id: function(id) {
                                var prev_image_id = self.getData(self.getPrev(methods.galleria.get_index_from_id(id))).image_id;
                                if (this._cache[prev_image_id] && this.get_cache_size() < self.getDataLength()) {
                                    return this.get_prev_uncached_image_id(prev_image_id);
                                } else {
                                    return prev_image_id;
                                }
                            },
                            // returns the image-id field of the first following image found whose comments aren't cached
                            get_next_uncached_image_id: function(id) {
                                var next_image_id = self.getData(self.getNext(methods.galleria.get_index_from_id(id))).image_id;
                                if (this._cache[next_image_id] && this.get_cache_size() < self.getDataLength()) {
                                    return this.get_next_uncached_image_id(next_image_id);
                                } else {
                                    return next_image_id;
                                }
                            },
                            // expanded request method: adds first pre-ceding and following uncached id to the request
                            expanded_request: function(id, finished) {
                                var id_array = (id instanceof Array) ? id : id.toString().split(',');
                                // a single ID was requested, so inject some extras so they can be cached in advance
                                if (id_array.length == 1) {
                                    var key = id_array[0];
                                    var prev = this.get_prev_uncached_image_id(key);
                                    var next = this.get_next_uncached_image_id(key);
                                    if (!this._cache[prev]) { id_array.unshift(prev); }
                                    if (!this._cache[next] && prev != next && id != next) { id_array.push(next); }
                                }
                                id_array = $.unique(id_array);
                                this.request(id_array, 0, finished);
                            },
                            // handles the HTTP request to load comments & cache the results
                            request: function(id, page, finished) {
                                var myself = this; // self is taken
                                var postdata = {
                                    action: 'get_comments',
                                    type:   'image',
                                    page:    page,
                                    id:      id.join(','),
                                    from:    window.parent.location.toString()
                                };
                                if ($.nplModal('get_setting', 'lang', false)) {
                                    postdata.lang = $.nplModal('get_setting', 'lang');
                                }
                                $.post(photocrati_ajax.url, postdata, function(data) {
                                    if (typeof(data) != 'object') {
                                        data = JSON.parse(data);
                                    }
                                    for (var ndx in data['responses']) {
                                        myself._cache[ndx] = data['responses'][ndx];
                                    }
                                    if (typeof finished == 'function') {
                                        finished(data);
                                    }
                                });
                            },
                            // find and load the next un-cached results
                            load_more: function(id) {
                                if (methods.nplModal.is_nextgen_gallery()
                                    &&  $.nplModal('get_setting', 'enable_routing', false)
                                    &&  $.nplModal('get_setting', 'enable_comments', false)) {
                                    var precache_ids = [];
                                    var prev = this.get_prev_uncached_image_id(id);
                                    var next = this.get_next_uncached_image_id(id);
                                    if (!this._cache[prev]) { precache_ids.push(prev); }
                                    if (!this._cache[next]) { precache_ids.push(next); }
                                    if ($.unique(precache_ids).length != 0) {
                                        this.expanded_request($.unique(precache_ids));
                                    }
                                }
                            },
                            // called after render(), initialize logic & events
                            init: function() {
                                if ($('#nggpl-comments-wrapper').length != 1) { return; }

                                // jquery's .val() fails with hidden fields
                                document.getElementById("ngg_comment_origin_url").value = window.location.href;

                                // It is much faster to change the target attribute globally here than through WP hooks
                                self.$('sidebar-container').find('a').each(function() {
                                    if ($(this).attr('id') == 'nggpl-comment-logout') {
                                        $(this).attr('href', $(this).attr('href') + '?redirect_to=' + window.location.toString());
                                    } else {
                                        $(this).attr('target', '_blank');
                                    }
                                });

                                $('#nggpl-respond-form').bind('submit', function (event) {
                                    event.preventDefault();
                                    var commentstatus = $('#nggpl-comment-status');
                                    self.$('sidebar-overlay').addClass('npl-sidebar-overlay-open');
                                    $.ajax({
                                        type: $(this).attr('method'),
                                        url: $(this).attr('action'),
                                        data: $(this).serialize(),
                                        dataType: 'json',
                                        success: function (data, status) {
                                            if (data.success == true) {
                                                $('#nggpl-comment').val('');
                                                $('#nggpl-comments-title').val('');
                                                var image_id = methods.galleria.get_current_image_id();
                                                methods.sidebars.comments.expanded_request(image_id, function(data) {
                                                    methods.sidebar.render(methods.sidebars.comments.get_type(), image_id);
                                                });
                                            } else {
                                                commentstatus.addClass('error')
                                                    .html(data);
                                                self.$('sidebar-overlay').removeClass('npl-sidebar-overlay-open');
                                            }
                                        },
                                        complete: function (jqXHR, status) {
                                        },
                                        error: function (jqXHR, status, error) {
                                            commentstatus.addClass('error').html(jqXHR.responseText);
                                            self.$('sidebar-overlay').removeClass('npl-sidebar-overlay-open');
                                        }
                                    });
                                });

                                $(".galleria-sidebar-container .nggpl-button, #nggpl-comment-form-wrapper input[type='submit']").each(function() {
                                    var $this = $(this);
                                    $this.css({
                                        'color': $.nplModal('get_setting', 'sidebar_button_color'),
                                        'background-color': $.nplModal('get_setting', 'sidebar_button_background')
                                    });
                                });

                                // handles 'Reply' links
                                $('.nggpl-reply-to-comment').bind('click', function(event) {
                                    event.preventDefault();
                                    // all that wordpress needs is the comment_parent value
                                    $('#nggpl-comment_parent').val($(this).data('comment-id'));
                                    $('#nggpl-comment-reply-status').removeClass('hidden');

                                    // IE has issues setting focus on invisible elements. Be wary
                                    $('#nggpl-commentform').find(':input').filter(':visible:first').focus();
                                    $('#nggpl-comments').velocity({
                                        scrollTop: $('#nggpl-comments-bottom').offset().top
                                    }, 'slow');
                                });

                                // handles "cancel reply" link
                                $('#nggpl-comment-reply-status a').bind('click', function(event) {
                                    event.preventDefault();
                                    $('#nggpl-comment_parent').val('0');
                                    $('#nggpl-comment-reply-status').addClass('hidden');
                                });

                                // handles comment AJAX pagination
                                $('#nggpl-comment-nav-below a').bind('click', function(event) {
                                    event.preventDefault();
                                    self.$('sidebar-overlay').addClass('npl-sidebar-overlay-open');
                                    var page_id = $(this).data('page-id');
                                    methods.sidebars.comments.request(
                                        [methods.galleria.get_current_image_id()],
                                        page_id,
                                        function() {
                                            methods.sidebar.render('comments', methods.galleria.get_current_image_id());
                                        }
                                    );
                                });

                               self.trigger('npl.sidebar.rendered');

                                if (methods.nplModal.is_nextgen_gallery()
                                    &&  $.nplModal('get_setting', 'enable_routing', false)
                                    &&  $.nplModal('get_setting', 'enable_sharing', false)) {
                                    $('#nggpl-comments-image-share-icons').removeClass('disabled');
                                    methods.share_icons.create(
                                        '#nggpl-comments-image-share-icons',
                                        methods.icons.get_overlay_color()
                                    );
                                }
                            },
                            // returns the display area content from cache
                            render: function(id) {
                                id = id || self.getData(self.getIndex()).image_id;
                                var cache = this._cache;
                                if (!this._cache[id]) {
                                    this.expanded_request(id, function(data) {
                                        self.$('sidebar-container').html(cache[id]['rendered_view']);
                                        methods.sidebars.comments.init();
                                        self.$('sidebar-overlay').removeClass('npl-sidebar-overlay-open');
                                    });
                                } else {
                                    self.$('sidebar-container').html(cache[id]['rendered_view']);
                                    self.$('sidebar-overlay').removeClass('npl-sidebar-overlay-open');
                                }
                            },
                            get_type: function() {
                                return 'comments';
                            },
                            events: {
                                bind: function() {
                                    if (methods.nplModal.is_nextgen_gallery()
                                    &&  $.nplModal('get_setting', 'enable_routing', false)
                                    &&  $.nplModal('get_setting', 'enable_comments', false)
                                    &&  !methods.nplModal.is_random_source()) {
                                        self.bind('image', this.image);
                                        self.bind('npl.init', this.npl_init);
                                        self.bind('npl.init.keys', this.npl_init_keys);
                                    }
                                },
                                npl_init: function() {
                                    // Adds comment toolbar button
                                    var comment_button = $('<i/>')
                                        .addClass('nggpl-toolbar-button-comment fa fa-comment')
                                        .attr({'title': $.nplModal('get_setting', 'i18n').toggle_social_sidebar})
                                        .click(function(event) {
                                            methods.sidebar.toggle(methods.sidebars.comments.get_type());
                                            event.preventDefault();
                                        });
                                    methods.thumbnails.register_button(comment_button);
                                },
                                _image_ran_once: false,
                                image: function() {
                                    if (methods.nplModal.is_nextgen_gallery()
                                    &&  $.nplModal('get_setting', 'enable_routing', false)
                                    &&  $.nplModal('get_setting', 'enable_comments', false)) {
                                        if (methods.sidebars.comments._image_ran_once) {
                                            // updates the sidebar
                                            methods.sidebars.comments.load_more(methods.galleria.get_current_image_id());
                                            if (methods.sidebar.is_open() && methods.sidebar.get_type() == methods.sidebars.comments.get_type()) {
                                                methods.sidebar.render(methods.sidebars.comments.get_type());
                                            }
                                        } else {
                                            // possibly display the comments sidebar at startup
                                            if ((($.nplModal('get_state').sidebar && $.nplModal('get_state').sidebar == methods.sidebars.comments.get_type())
                                                ||  $.nplModal('get_setting', 'display_comments'))
                                            &&  !$.nplModal('mobile.browser.any')) {
                                                methods.sidebar.open(methods.sidebars.comments.get_type());
                                            }
                                        }
                                        methods.sidebars.comments._image_ran_once = true;
                                    }
                                },
                                npl_init_keys: function(event) {
                                    var input_types = methods.galleria.get_keybinding_exclude_list();
                                    if (methods.nplModal.is_nextgen_gallery()
                                        &&  $.nplModal('get_setting', 'enable_routing', false)
                                        &&  $.nplModal('get_setting', 'enable_comments', false))
                                    {
                                        self.attachKeyboard({
                                            // spacebar
                                            32: function () {
                                                if (!$(document.activeElement).is(input_types)) {
                                                    methods.sidebar.toggle(methods.sidebars.comments.get_type());
                                                }
                                            }
                                        });
                                    }
                                }
                            }
                        }
                    },
                    thumbnails: {
                        _aip: false,
                        _is_open: true,
                        is_aip: function(state) {
                            if (typeof state != 'undefined') {
                                this._aip = state;
                            } else {
                                return this._aip;
                            }
                        },
                        is_open: function(state) {
                            if (typeof state != 'undefined') {
                                this._is_open = state;
                            } else {
                                return this._is_open;
                            }
                        },
                        toggle: function() {
                            if (!this.is_aip()) {
                                if (this.is_open()) {
                                    this.close();
                                } else {
                                    this.open();
                                }
                                this.is_aip(true);
                            }
                        },
                        open: function() {
                            self.$('thumbnails-container, dock-toggle-container, info').velocity(
                                {bottom: '+=' + self.$('thumbnails-container').height() + 'px'},
                                {complete: function() {
                                    methods.thumbnails.is_open(true);
                                    methods.thumbnails.is_aip(false);
                                    self.$('container').addClass('npl-thumbnails-open');
                                    $(window).trigger('resize');
                                    self.trigger('npl.thumbnails.opened');
                                    $('.galleria-dock-toggle-container i').toggleClass('fa-angle-up fa-angle-down');
                                }}
                            );
                        },
                        close: function() {
                            self.$('thumbnails-container, dock-toggle-container, info').velocity(
                                {bottom: '-=' + self.$('thumbnails-container').height() + 'px'},
                                {complete: function() {
                                    methods.thumbnails.is_open(false);
                                    methods.thumbnails.is_aip(false);
                                    self.$('container').removeClass('npl-thumbnails-open');
                                    $(window).trigger('resize');
                                    self.trigger('npl.thumbnails.closed');
                                    $('.galleria-dock-toggle-container i').toggleClass('fa-angle-up fa-angle-down');
                                }}
                            );
                        },
                        adjust_container: function() {
                            // this keeps the toggle button at the top of the info box & above the thumbnails container
                            var bottom = self.$('thumbnails-container').height();
                            bottom += parseInt(self.$('thumbnails-container').css('bottom'));
                            if (methods.info.is_open()) {
                                bottom += self.$('info').height();
                            }
                            self.$('dock-toggle-container').css({
                                bottom: bottom + 'px',
                                left: (self.$('stage').width() / 2) + 'px'
                            });
                        },
                        _buttons: [],
                        register_button: function(button) {
                            var wrapper = $('<span class="nggpl-button nggpl-toolbar-button"/>');
                            if ($.nplModal('get_setting', 'icon_background_enabled', false)
                                &&  $.nplModal('get_setting', 'icon_background_rounded', false)) {
                                wrapper.addClass('nggpl-rounded');
                            }
                            wrapper.html(button);
                            this._buttons.push(wrapper);
                            $(self._dom.stage).append(wrapper);
                        },
                        get_registered_buttons: function() {
                            return this._buttons;
                        },
                        events: {
                            bind: function() {
                                self.bind('loadfinish', methods.thumbnails.adjust_container);
                                self.bind('loadfinish', this.loadfinish);
                                self.bind('rescale', methods.thumbnails.adjust_container);
                                self.bind('npl.sidebar.opened', methods.thumbnails.adjust_container);
                                self.bind('npl.sidebar.closed', methods.thumbnails.adjust_container);
                                self.bind('npl.init', this.npl_init);
                                self.bind('npl.init.complete', this.npl_init_complete);
                            },
                            npl_init: function() {
                                self.$('container').addClass('npl-thumbnails-open');
                                self.$('thumbnails-container').css({background: $.nplModal('get_setting', 'carousel_background_color')});

                                // create carousel next/prev links
                                var next_thumbs_button = $('<i/>')
                                    .addClass('fa fa-angle-right')
                                    .css({color: methods.icons.get_color()});
                                var prev_thumbs_button = $('<i/>')
                                    .addClass('fa fa-angle-left')
                                    .css({color: methods.icons.get_color()});
                                $(self._dom.stage).append(next_thumbs_button);
                                $(self._dom.stage).append(prev_thumbs_button);
                                self.append({'thumb-nav-left': prev_thumbs_button});
                                self.append({'thumb-nav-right': next_thumbs_button});

                                // Create thumbnails-container toggle button
                                self.addElement('dock-toggle-container');
                                var dock_toggle_container = self.$('dock-toggle-container')
                                    .css({background: $.nplModal('get_setting', 'carousel_background_color')});
                                var dock_toggle_button = $('<i/>').addClass('fa fa-angle-down')
                                    .css({color: $.nplModal('get_setting', 'carousel_text_color')});
                                $(self._dom.stage).append(dock_toggle_button);
                                self.append({'dock-toggle-container': dock_toggle_button});
                                dock_toggle_container.click(self.proxy(function() {
                                    methods.thumbnails.toggle();
                                }));

                                // Add playback controls
                                var play_button = $('<i/>')
                                    .addClass('nggpl-toolbar-button-play fa fa-play')
                                    .attr({'title': $.nplModal('get_setting', 'i18n').play_pause})
                                    .click(function(event) {
                                        event.preventDefault();
                                        self.playToggle();
                                        $(this).toggleClass('fa-play');
                                        $(this).toggleClass('fa-pause');
                                    });
                                if (this._playing) {
                                    play_button.removeClass('fa-play').addClass('fa-pause');
                                }
                                methods.thumbnails.register_button(play_button);

                                // Add fullscreen and margin-less controls
                                if ($.nplModal('get_setting', 'enable_fullscreen', false)
                                &&  $.nplModal('fullscreen.has_support')
                                &&  !$.nplModal('mobile.browser.any'))
                                {
                                    var fullscreen_button = $('<i/>')
                                        .addClass('nggpl-toolbar-button-fullscreen fa fa-arrows-alt')
                                        .attr({'title': $.nplModal('get_setting', 'i18n').toggle_fullsize})
                                        .click(function(event) {
                                            event.preventDefault();
                                            $.nplModal('fullscreen.toggle');
                                            $(this).toggleClass('fa-arrows-alt');
                                            $(this).toggleClass('fa-expand');
                                        });
                                    methods.thumbnails.register_button(fullscreen_button);
                                }

                                // add info controls; handles animation of both the info & dock-toggle-container divs
                                var info_button = $('<i/>')
                                    .addClass('nggpl-toolbar-button-info fa fa-info')
                                    .attr({'title': $.nplModal('get_setting', 'i18n').toggle_image_info})
                                    .click(self.proxy(function(event) {
                                        event.preventDefault();
                                        methods.info.toggle();
                                    }));
                                methods.thumbnails.register_button(info_button);
                            },
                            npl_init_complete: function() {
                                var display_buttons = methods.thumbnails.get_registered_buttons();
                                // assign all of our buttons a (possibly custom) color
                                for (i = 0; i <= (display_buttons.length - 1); i++) {
                                    display_buttons[i].css({
                                        'color': methods.icons.get_color(),
                                        'background-color': methods.icons.get_background()
                                    });
                                }
                                self.addElement('nextgen-buttons');
                                self.append({'nextgen-buttons': display_buttons});
                                self.prependChild('thumbnails-container', 'nextgen-buttons');
                                self.appendChild('container', 'dock-toggle-container');

                                if (!$.nplModal('mobile.browser.any')) {
                                    self.addIdleState(self.get('dock-toggle-button'), {opacity: 0});
                                }
                            },
                            // Galleria.js does NOT alawys play nicely if we hide the carousel before the first
                            // time the loadfinished event has been triggered.
                            _loadfinish_ran_once: false,
                            loadfinish: function() {
                                if (methods.thumbnails.events._loadfinish_ran_once)
                                    return;
                                // wait until init.complete to hide these so users can still know they exist
                                if (!$.nplModal('get_setting', 'display_carousel', true) || Galleria.IPHONE || Galleria.IPAD || navigator.userAgent.match('CriOS')) {
                                    methods.thumbnails.toggle();
                                }
                                methods.thumbnails.events._loadfinish_ran_once = true;
                            }
                        }
                    },
                    info: {
                        _aip: false,
                        _is_open: false,
                        is_aip: function(state) {
                            if (typeof state != 'undefined') {
                                this._aip = state;
                            } else {
                                return this._aip;
                            }
                        },
                        is_open: function(state) {
                            if (typeof state != 'undefined') {
                                this._is_open = state;
                            } else {
                                return this._is_open;
                            }
                        },
                        toggle: function() {
                            if (!this.is_aip()) {
                                if (!self.$('info').is(':visible')) {
                                    this.open();
                                } else {
                                    this.close();
                                }
                                this.is_aip(true);
                            }
                        },
                        open: function() {
                            // hide our info box before animating it into onto the screen
                            methods.info.is_open(true);
                            var info = self.$('info');
                            info.css({height: 'auto'});
                            var target = info.height();
                            info.css({
                                height: '0px',
                                display: 'block'
                            });
                            self.$('dock-toggle-container').velocity(
                                {bottom: '+=' + target + 'px'}
                            );
                            info.velocity(
                                {height: target + 'px'},
                                {complete: function() {
                                    info.css({height: 'auto'});
                                    setTimeout(function() {
                                        self.$('dock-toggle-container').css({
                                            bottom: (self.$('container').height() - info.position().top) + 'px'
                                        });
                                    }, 90);
                                    self.$('info-text').velocity({opacity: 1});
                                    methods.info.is_aip(false);
                                    self.trigger('npl.info.opened');
                                }}
                            );
                        },
                        close: function() {
                            var info = self.$('info');
                            self.$('info-text').velocity({opacity: 0}, {duration: 'fast'});
                            self.$('dock-toggle-container').velocity(
                                {bottom: '-=' + info.height() + 'px'}
                            );
                            info.velocity(
                                {height: '0px'},
                                {complete: function() {
                                    info.css({
                                        display: 'none',
                                        height: 'auto'
                                    });
                                    methods.info.is_open(false);
                                    methods.info.is_aip(false);
                                    self.trigger('npl.info.closed');
                                }}
                            );
                        },
                        events: {
                            bind: function() {
                                self.bind('npl.init', this.npl_init);
                                self.bind('loadfinish', this.loadfinish);
                            },
                            npl_init: function() {
                                // Add social share icons to the infobar. ID is important, sidebars could add their own icons
                                self.prependChild(
                                    'info-text',
                                    $('<div/>')
                                        .attr('id', 'galleria-image-share-icons')
                                        .attr('class', 'galleria-image-share-icons')
                                );
                                if (carousel_text_color = $.nplModal('get_setting', 'carousel_text_color')) {
                                    self.$('info-title').css({color: carousel_text_color});
                                    self.$('info-description').css({color: carousel_text_color});
                                }
                                self.$('info, info-text, info-title, info-description').css({background: $.nplModal('get_setting', 'carousel_background_color')});
                            },
                            _loadfinish_ran_once: false,
                            loadfinish: function() {
                                // anchors in our image captions / descriptions must have target=_blank as we are in an iframe
                                self.$('info-title, info-description').find('a').each(function() {
                                    $(this).attr('target', '_blank');
                                    $(this).css('color', methods.icons.get_color());
                                });

                                // possibly display the image info panel at startup
                                if ($.nplModal('get_setting', 'display_captions')) {
                                    if (!methods.info.events._loadfinish_ran_once) {
                                        setTimeout(function() {
                                            methods.info.open();
                                        }, 90);
                                    }
                                }
                                methods.info.events._loadfinish_ran_once = true;
                            }
                        }
                    },
                    icons: {
                        get_color: function() {
                            return $.nplModal('get_setting', 'icon_color');
                        },
                        get_background: function() {
                            var iconcolor = $.nplModal('get_setting', 'carousel_background_color');
                            if ($.nplModal('get_setting', 'icon_background_enabled', false)) {
                                iconcolor = $.nplModal('get_setting', 'icon_background');
                            }
                            return iconcolor;
                        },
                        get_overlay_color: function() {
                            return $.nplModal('get_setting', 'overlay_icon_color');
                        }
                    },
                    nplModal: {
                        close: function() {
                            $.nplModal('close_modal');
                        },
                        is_random_source: function() {
                            var gallery = $.nplModal('get_gallery_from_id', $.nplModal('get_state').gallery_id);
                            return ($.inArray(gallery.source, ['random', 'random_images']) != -1);
                        },
                        is_nextgen_gallery: function() {
                            return $.nplModal('get_state').gallery_id != '!';
                        },
                        is_nextgen_widget: function() {
                            var retval = false;
                            var gallery = $.nplModal('get_gallery_from_id', $.nplModal('get_state').gallery_id);
                            var slug = gallery.slug;
                            if (slug) {
                                retval = slug.indexOf('widget-ngg-images') !== -1;
                            }
                            return retval;
                        },
                        events: {
                            bind: function() {
                                if (Galleria.IPAD || Galleria.IPHONE) {
                                    self.$('container').addClass('nggpl-ios-browser');
                                }

                                // update the parent url when a new image has been chosen or the slideshow advances
                                if (methods.nplModal.is_nextgen_gallery()) {
                                    self.bind('image', this.image);
                                }

                                // handle updates to the current url once opened; most likely due to the back/forward button
                                if (methods.nplModal.is_nextgen_gallery()
                                    &&  $.nplModal('get_setting', 'enable_routing', false)
                                    &&  $.nplModal('get_setting', 'enable_comments', false)) {
                                    $('#npl_content').on('npl.url_handler', this.unhandled_url_change);
                                }

                                if ($.nplModal('get_setting', 'protect_images', false)) {
                                    $('.galleria-image').bind('dragstart', function(event) {
                                        event.preventDefault();
                                    });
                                    self.bind('npl.init', this.npl_init);
                                    self.bind('npl.init.complete', this.npl_init_complete);
                                }

                                jQuery('#npl_content').bind('npl.closing', this.closing);
                            },
                            npl_init: function() {
                                self.addElement('image-protection');
                                document.oncontextmenu = function(event) {
                                    event = event || window.event;
                                    event.preventDefault();
                                };
                            },
                            npl_init_complete: function() {
                                self.prependChild('images', 'image-protection');
                            },
                            _image_ran_once: false,
                            image: function() {
                                if (methods.nplModal.events._image_ran_once) {
                                    if (!methods.nplModal.is_random_source()) {
                                        var image_id = self.getData(self.getIndex()).image_id;
                                        var sidebar_string = methods.sidebar.is_open() ? '/' + methods.sidebar.get_type() : '';
                                        var state = $.nplModal('get_state');
                                        state.image_id = image_id;
                                        $.nplModal('set_state', state);
                                        if ($.nplModal('get_setting', 'enable_routing', false)) {
                                            $.nplModal(
                                                'router.navigate',
                                                $.nplModal('get_setting', 'router_slug') + '/' + $.nplModal('get_state').slug + '/' + image_id + sidebar_string,
                                                true
                                            );
                                        }
                                    }
                                }
                                methods.nplModal.events._image_ran_once = true;
                            },
                            unhandled_url_change: function(event, state) {
                                for (var i = 0; i <= (self.getDataLength() - 1); i++) {
                                    if (state.image_id == self.getData(i).image_id) {
                                        self.show(i);
                                    }
                                }
                                if (methods.sidebar.is_open() && state.sidebar == null) {
                                    methods.sidebar.close();
                                } else if (!$.nplModal('mobile.browser.any')
                                       &&  !methods.sidebar.is_open()
                                       &&  typeof state.sidebar !== 'undefined'
                                       &&  state.sidebar !== null) {
                                    methods.sidebar.open(state.sidebar);
                                }
                            },
                            closing: function(event) {
                                // without this our bound keys will continue to activate even aften
                                // Galleria.destroy() has been called and the container div emptied
                                self.detachKeyboard();
                            }
                        }
                    },
                    galleria: {
                        get_displayed_gallery_setting: function(name, def) {
                            var tmp = '';
                            var gallery = $.nplModal('get_gallery_from_id', $.nplModal('get_state').gallery_id);
                            if (gallery && typeof gallery.display_settings[name] != 'undefined') {
                                tmp = gallery.display_settings[name];
                            } else {
                                tmp = def;
                            }
                            if (tmp == 1) tmp = true;
                            if (tmp == 0) tmp = false;
                            return tmp;
                        },
                        get_keybinding_exclude_list: function() {
                            return 'textarea, input';
                        },
                        get_current_image_id: function() {
                            return self.getData(self.getIndex()).image_id;
                        },
                        // returns the Galleria image-index based on the provided image id
                        get_index_from_id: function(id) {
                            var retval = null;
                            for (var i = 0; i <= (self.getDataLength() - 1); i++) {
                                if (id == self.getData(i).image_id) {
                                    retval = i;
                                }
                            }
                            return retval;
                        },
                        events: {
                            bind: function() {
                                self.bind('touchmove', this.touchmove);
                                self.bind('npl.init', this.npl_init);
                                self.bind('npl.init.keys', this.npl_init_keys);
                                self.bind('npl.init.complete', this.npl_init_complete);
                                self.bind('loadstart', this.loadstart);
                                self.bind('loadfinish', this.loadfinish);
                                $(window).on('resize orientationchange', this.browserchanged);
                            },
                            browserchanged: function(event) {
                                setTimeout(function() {
                                    self.rescale();
                                }, 200);
                            },
                            npl_init: function() {
                                // for some reason this isn't an option that can be passed at startup
                                self.setPlaytime(($.nplModal('get_setting', 'slideshow_speed', 5) * 1000));
                                self.$('container').css({background: $.nplModal('get_setting', 'background_color')});

                                // Create next / back links
                                var next_image_button = $('<i/>')
                                    .addClass('fa fa-angle-right')
                                    .css({color: methods.icons.get_overlay_color()});
                                var prev_image_button = $('<i/>')
                                    .addClass('fa fa-angle-left')
                                    .css({color: methods.icons.get_overlay_color()});
                                $(self._dom.stage).append(next_image_button);
                                $(self._dom.stage).append(prev_image_button);
                                self.append({'image-nav-left': prev_image_button});
                                self.append({'image-nav-right': next_image_button});

                                self.$('counter').css({color: methods.icons.get_overlay_color()});
                            },
                            npl_init_keys: function(event) {
                                var input_types = methods.galleria.get_keybinding_exclude_list();
                                self.attachKeyboard({
                                    left: function() {
                                        if (!$(document.activeElement).is(input_types)) {
                                            this.prev();
                                        }
                                    },
                                    right: function() {
                                        if (!$(document.activeElement).is(input_types)) {
                                            this.next();
                                        }
                                    },
                                    down: function() {
                                        if (!$(document.activeElement).is(input_types)) {
                                            methods.thumbnails.toggle();
                                        }
                                    },
                                    up: function() {
                                        if (!$(document.activeElement).is(input_types)) {
                                            methods.info.toggle();
                                        }
                                    },
                                    // escape key
                                    27: function(event) {
                                        event.preventDefault();
                                        methods.nplModal.close();
                                    },
                                    // 'f' for 'f'ullscreen
                                    70: function() {
                                        if (!$(document.activeElement).is(input_types)) {
                                            if ($.nplModal('get_setting', 'enable_fullscreen', false)
                                            &&  $.nplModal('fullscreen.has_support')) {
                                                $.nplModal('fullscreen.toggle');
                                            }
                                        }
                                    }
                                });
                            },
                            npl_init_complete: function() {
                                if (!$.nplModal('mobile.browser.any')) {
                                    self.addIdleState(self.get('counter'),            {opacity: 0});
                                    self.addIdleState(self.get('image-nav-left'),     {opacity: 0});
                                    self.addIdleState(self.get('image-nav-right'),    {opacity: 0});
                                }
                            },
                            touchmove: function(event) {
                                // prevent scrolling on elements without the 'scrollable' class
                                if (!$('.scrollable').has($(event.target)).length) {
                                    event.preventDefault();
                                }
                            },
                            loadstart: function(event) {
                                if (!event.cached) {
                                    var button = $('#npl_button_close').find('i');
                                    button.removeClass('fa-times-circle');
                                    button.addClass('fa-spinner');
                                    button.addClass('fa-spin');
                                }
                            },
                            loadfinish: function(event) {
                                var button = $('#npl_button_close').find('i');
                                button.addClass('fa-times-circle');
                                button.removeClass('fa-spinner');
                                button.removeClass('fa-spin');
                            }
                        }
                    },
                    share_icons: {
                        strip_html: function(html) {
                            var tmp = document.createElement('div');
                            tmp.innerHTML = html;
                            return tmp.textContent || tmp.innerText || "";
                        },
                        create: function(target, iconcolor) {
                            if (methods.nplModal.is_random_source()
                            ||  methods.nplModal.is_nextgen_widget()
                            ||  !methods.nplModal.is_nextgen_gallery()
                            ||  !$.nplModal('get_setting', 'enable_routing', false)) {
                                return false;
                            }

                            var id = self.getData(self.getIndex()).image_id;
                            var data = self.getData(self.getIndex());
                            var base_url = encodeURIComponent(methods.share_icons.get_share_url(id));
                            var url = encodeURIComponent(window.location.toString());
                            var title = methods.share_icons.strip_html(data.title);
                            var summary = methods.share_icons.strip_html(data.description);

                            var twitter_icon = $('<li/>').html(
                                $('<a/>', {'href': 'https://twitter.com/share?url=' + methods.share_icons.get_share_url(id, 'full'),
                                    'target': '_blank',
                                    'class': 'nggpl-comment-tweet-button',
                                    'title': $.nplModal('get_setting', 'i18n').share.twitter})
                                    .css({color: iconcolor})
                                    .html($('<i/>', {'class': 'fa fa-twitter-square'}))
                            );

                            var googlep_icon = $('<li/>').html(
                                $('<a/>', {'href': 'https://plus.google.com/share?url=' + base_url,
                                    'target': '_blank',
                                    'class': 'nggpl-comment-googlep-button',
                                    'title': $.nplModal('get_setting', 'i18n').share.googlep})
                                    .css({color: iconcolor})
                                    .html($('<i/>', {'class': 'fa fa-google-plus-square'}))
                            );

                            var facebook_url = '';
                            if (typeof $.nplModal('get_setting', 'facebook_app_id') != 'undefined') {

                                facebook_url = 'https://www.facebook.com/dialog/feed?app_id=';
                                facebook_url += $.nplModal('get_setting', 'facebook_app_id');
                                facebook_url += '&link=' + encodeURIComponent(window.location.toString());
                                if (summary.length > 0)
                                    facebook_url += '&description=' + summary.trim();
                                facebook_url += '&picture=' + data.image.trim();
                                facebook_url += '&redirect_uri=' + encodeURIComponent(window.location.toString());
                            } else {
                                facebook_url = 'https://www.facebook.com/sharer/sharer.php?s=100';
                                facebook_url += '&p[url]=' + encodeURIComponent(methods.share_icons.get_share_url(id, 'full'));
                                if (title.length > 0)
                                    facebook_url += '&p[title]=' + title.trim();
                                if (summary.length > 0)
                                    facebook_url += '&p[summary]=' + summary.trim();
                                facebook_url += '&p[images][0]=' + encodeURIComponent(data.image).trim();
                            }

                            var facebook_icon = $('<li/>').html(
                                $('<a/>', {
                                    'href': facebook_url,
                                    'target': '_blank',
                                    'class': 'nggpl-comment-facebook-button',
                                    'title': $.nplModal('get_setting', 'i18n').share.facebook
                                }).css({color: iconcolor})
                                  .html($('<i/>', {'class': 'fa fa-facebook-square'}))
                            );

                            var pinterest_url = encodeURIComponent(methods.share_icons.get_share_url(id, 'full'));
                            pinterest_url += '&url=' + url;
                            pinterest_url += '&media=' + data.image;
                            pinterest_url += '&description=' + summary;
                            var pinterest_icon = $('<li/>').html(
                                $('<a/>', {'href': 'http://pinterest.com/pin/create/button/?s=100' + pinterest_url,
                                    'target': '_blank',
                                    'class': 'nggpl-comment-pinterest-button',
                                    'title': $.nplModal('get_setting', 'i18n').share.pinterest})
                                    .css({color: iconcolor})
                                    .html($('<i/>', {'class': 'fa fa-pinterest-square'}))
                            );

                            var icons = [twitter_icon, googlep_icon, facebook_icon, pinterest_icon];

                            target = $(target);
                            target.html('');
                            var ul = $('<ul/>').appendTo(target);
                            target.find('ul').append(icons);
                        },
                        get_share_url: function(id, named_size) {
                            if (typeof(named_size) == 'undefined') {
                                named_size = 'thumb';
                            }

                            var gallery_id = $.nplModal('get_state').gallery_id;
                            var base_url = $.nplModal('get_setting', 'share_url')
                                .replace('{gallery_id}', gallery_id)
                                .replace('{image_id}', id)
                                .replace('{named_size}', named_size);
                            var site_url_link = $('<a/>').attr('href', $.nplModal('get_setting', 'wp_site_url'))[0];
                            var parent_link   = $('<a/>').attr('href', window.location.toString())[0];
                            var base_link     = $('<a/>').attr('href', base_url)[0];

                            // check if site is in a sub-directory and shorten the prefix
                            if (parent_link.pathname.indexOf(site_url_link.pathname) >= 0) {
                                parent_link.pathname = parent_link.pathname.substr(site_url_link.pathname.length);
                            }
                            // shorten url by removing their common prefix
                            if (parent_link.pathname.indexOf(base_link.pathname) >= 0) {
                                parent_link.pathname = parent_link.pathname.substr(parent_link.pathname.length);
                            }

                            // this is odd but it's just how the 'share' controller expects it
                            base_link.search = parent_link.search;
                            if (base_link.search.length > 0) {
                                base_link.search += '&';
                            }
                            base_link.search += 'uri=' + parent_link.pathname;

                            return base_link.href;
                        },
                        events: {
                            bind: function() {
                                self.bind('image', this.image);
                            },
                            image: function() {
                                if (methods.nplModal.is_nextgen_gallery()
                                    &&  $.nplModal('get_setting', 'enable_routing', false)
                                    &&  $.nplModal('get_setting', 'enable_sharing', false)) {
                                    var iconcolor = $.nplModal('get_setting', 'carousel_text_color') ? $.nplModal('get_setting', 'carousel_text_color') : methods.icons.get_color();
                                    methods.share_icons.create(
                                        '#galleria-image-share-icons',
                                        iconcolor
                                    );
                                }
                            }
                        }
                    }
                };

                // Load our modules
                methods.galleria.events.bind();
                methods.nplModal.events.bind();
                methods.thumbnails.events.bind();
                methods.info.events.bind();
                methods.share_icons.events.bind();
                methods.sidebar.events.bind();
                methods.sidebars.comments.events.bind();

                $(self._target).trigger('npl.ready', {galleria_theme: self, methods: methods});

                self.trigger('npl.init');
                self.trigger('npl.init.keys');
                self.trigger('npl.init.complete');
            }
        });
    };

}(jQuery));
