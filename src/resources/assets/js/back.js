import Vue from 'vue';
import Sortable from 'sortablejs';
import Notifications from 'vue-notification';
Vue.use(Notifications);

Vue.directive('sortable', {
    inserted: function (el, binding) {
        new Sortable(el, binding.value || {})
    }
})

import store from './store/store';

import quiz from './components/quiz';
import question from './components/question';
import items from './components/items';

new Vue({
    el: '#quiz',
    store,
});
