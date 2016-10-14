/**
 * Queue server select (settings)
 *
 * @module package/quiqqer/queuemanager/bin/controls/ServerSelect
 * @author www.pcsg.de (Patrick Müller)
 *
 * @require qui/controls/Control
 * @require qui/controls/buttons/Select
 * @require Ajax
 * @require Locale
 */
define('package/quiqqer/queuemanager/bin/controls/ServerSelect', [

    'qui/controls/Control',
    'qui/controls/buttons/Select',

    'Ajax',
    'Locale'

], function (QUIControl, QUISelect, QUIAjax, QUILocale) {
    "use strict";

    return new Class({
        Extends: QUIControl,
        Type   : 'package/quiqqer/queuemanager/bin/controls/ServerSelect',

        Binds: [
            '$onImport',
            'create'
        ],

        options: {},

        initialize: function (options) {
            this.parent(options);

            this.$Input = null;

            this.addEvents({
                onImport: this.$onImport
            });
        },

        /**
         * Event: onImport
         */
        $onImport: function () {
            var self = this;

            this.$Input = this.getElm();
            this.$Input.setStyle('display', 'none');

            this.$fetchQueueServers().then(function (servers) {
                if (!servers.length) {
                    new Element('p', {
                        html: QUILocale.get(
                            'quiqqer/queuemanager',
                            'controls.serverselect.no.servers.found'
                        )
                    }).inject(self.$Input, 'after');

                    return;
                }

                var ServerSelect = new QUISelect({
                    styles: {
                        width: '40%'
                    },
                    events: {
                        onChange: function(value) {
                            self.$Input.value = value;
                        }
                    }
                }).inject(self.$Input, 'after');

                var first        = false;

                for (var i = 0, len = servers.length; i < len; i++) {
                    ServerSelect.appendChild(
                        servers[i].title + '<br><i>' + servers[i].description + '</i>' ,
                        servers[i].class,
                        'fa fa-server'
                    );

                    if (!first) {
                        first = servers[i].class;
                    }
                }

                if (self.$Input.value.length) {
                    ServerSelect.setValue(self.$Input.value);
                } else {
                    ServerSelect.setValue(first);
                }
            });
        },

        /**
         * Fetch all available queue servers
         *
         * @return {Promise}
         */
        $fetchQueueServers: function () {
            return new Promise(function (resolve, reject) {
                QUIAjax.get('package_quiqqer_queuemanager_ajax_getQueueServers', resolve, {
                    'package': 'quiqqer/queuemanager',
                    onError  : reject
                });
            });
        }
    });
});