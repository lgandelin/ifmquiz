import Vue from 'vue';
import VueCountdown from '@xkeshi/vue-countdown';

Vue.component('countdown', VueCountdown);

new Vue({
    el: '#countdown',
    methods: {
        countdown: function () {
            this.counting = true;
        },
        countdownend: function () {
            this.counting = false;
        },
    },
});