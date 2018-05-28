import Vue from 'vue';

Vue.component('question', {
    template: '#question-template',
    props: {
        question_number: 0,
        title: {
            default: '',
            type: String
        },
        description: {
            default: '',
            type: String
        },
        type: {
            default: 1,
            type: Number
        },
        items: Array,
        items_left: Array,
        items_right: Array,
    },
    data: function() {
        return {
            is_opened: true,
            menu_opened: false,
            new_item_title: '',
            new_item_left_title : '',
            new_item_right_title : '',
            new_item_right_associated_item : 0,
        }
    },
    methods: {
        add_item: function(question_number) {
            this.$store.commit('add_item', {
                title: this.new_item_title,
                correct: false,
                question_number: question_number
            });

            this.new_item_title = '';
        },
        update_question_title: function(e, question_number) {
            this.$store.commit('update_question_title', {
                title: e.target.value,
                question_number: question_number,
            })
        },
        update_question_description: function(e, question_number) {
            this.$store.commit('update_question_description', {
                description: e.target.value,
                question_number: question_number,
            })
        },
        update_question_type: function(e, question_number) {
            this.$store.commit('update_question_type', {
                type: parseInt(e.target.value),
                question_number: question_number,
            });
        },
        add_item_left: function(question_number) {
            this.$store.commit('add_item_left', {
                title: this.new_item_left_title,
                question_number: question_number
            });

            this.new_item_left_title = '';
        },
        add_item_right: function(question_number) {
            this.$store.commit('add_item_right', {
                title: this.new_item_right_title,
                associated_item: this.new_item_right_associated_item,
                question_number: question_number,
            });

            this.new_item_right_title = '';
            this.new_item_right_associated_item = 0;
        },
    }
});