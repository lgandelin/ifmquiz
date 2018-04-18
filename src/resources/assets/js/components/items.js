import Vue from 'vue';

Vue.component('item-text', {
    template: '#item-text-template',
    props: {
        item_number: 0,
        question_number: 0,
        title: {
            default: '',
            type: String
        },
        correct: {
            default: false,
            type: Boolean,
        },
    },
    methods: {
        check_item: function(item_number, question_number) {
            this.$store.commit('check_item', {
                item_number: item_number,
                question_number: question_number
            });
        },
        delete_item: function(item_number, question_number) {
            this.$store.commit('delete_item', {
                item_number: item_number,
                question_number: question_number
            });
        },
        update_item_title: function(e, item_number, question_number) {
            this.$store.commit('update_item_title', {
                title: e.target.value,
                item_number: item_number,
                question_number: question_number
            });
        }
    },
});

Vue.component('item-left', {
    template: '#item-left-template',
    props: {
        item_number: 0,
        question_number: 0,
        title: {
            default: '',
            type: String
        },
    },
    methods: {
        delete_item_left: function (item_number, question_number) {
            this.$store.commit('delete_item_left', {
                item_number: item_number,
                question_number: question_number
            });
        },
        update_item_left_title: function(e, item_number, question_number) {
            this.$store.commit('update_item_left_title', {
                title: e.target.value,
                item_number: item_number,
                question_number: question_number
            });
        }
    }
});

Vue.component('item-right', {
    template: '#item-right-template',
    props: {
        item_number: 0,
        question_number: 0,
        title: {
            default: '',
            type: String
        },
        associated_item: {
            default: 0,
            type: Number
        }
    },
    methods: {
        delete_item_right: function(item_number, question_number) {
            this.$store.commit('delete_item_right', {
                item_number: item_number,
                question_number: question_number
            });
        },
        update_item_right_title: function(e, item_number, question_number) {
            this.$store.commit('update_item_right_title', {
                title: e.target.value,
                item_number: item_number,
                question_number: question_number
            });
        }
    }
});