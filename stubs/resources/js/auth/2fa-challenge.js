import Vue from 'vue';
import PageContent from './_components/2FAChallenge.vue';

(function() {
    new Vue({
        components: {
            PageContent,
        },
        el: '#vueable',
        template: '<PageContent v-bind="contentProps" />',
        data: {
            contentProps
        },
    });
})();
