<div id="quiz">
    <div class="question" v-for="(question, index) in questions">
        <textarea class="title">@{{ question.title }}</textarea>
        <textarea class="description">@{{ question.description }}</textarea>
    </div>

    <div class="question add-question">
        <textarea class="title" placeholder="Ajouter une question" v-model="new_question_title"></textarea>
        <textarea class="description" placeholder="Ajouter une description" v-model="new_question_description"></textarea>
        <button class="add-button" @click="valid_add">OK</button>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script>

<script>
    var app = new Vue({
        el: '#quiz',
        data: {
            questions: [],
            new_question_title: "",
            new_question_description: "",
        },
        mounted: function() {
            axios.get("/questions")
                .then(function(response) {
                    app.questions = response.data
                })
        },
        methods: {
            valid_add: function() {
                this.questions.push({
                    title: this.new_question_title,
                    description: this.new_question_description,
                });

                this.new_question_title = '';
                this.new_question_description = '';
            }
        }
    })
</script>

<style type="text/css">
    .question {
        display: block;
        margin-bottom: 2rem;
        padding: 2rem;

        background: #ccc;
    }

    .title {
        height: 50px;
    }

    .description {
        width: 300px;
        height: 50px;
    }
    
    .add-question {
        background: #eee;
    }

    .add-button {
        display: inline-block;
        vertical-align: top;
    }
</style>