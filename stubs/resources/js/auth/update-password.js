import Vue from 'vue';
import BodyComponent from '../dashboard/_components/BodyComponent.vue';
import PageContent from './_components/UpdatePassword.vue';

(function() {
    new Vue({
        components: {
            BodyComponent,
        },
        el: '#vueable',
        template: '<BodyComponent :page="page" :page-content="PageContent" :content-props="contentProps" />',
        data: {
            page,
            PageContent,
            contentProps
        },
    });
})();
