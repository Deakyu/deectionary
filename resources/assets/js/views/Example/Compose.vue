<template>
<div>
    <form class="form form--compose" @submit.prevent="compose">
        <h1 class="form__title">Daily Example Compose</h1>
        
        <div class="datepicker datepicker--compose">
            <input id="dateInput" type="date" v-model="date" @change="wordByDate">
            <button @click="calendarClick"><i class="fa fa-calendar" aria-hidden="true"></i></button>
        </div>

        <div class="form__group" v-if="words.length>0">
            <div class="form__control" style="text-align:left;padding-bottom:0;">
                <div class="word-tooltip" v-for="word in words">
                    <!-- <span class="form__tags" :id="word.id" v-for="word in words">{{word.word}}<a @click="remove(word.id)">&times;</a></span> -->
                    <span class="form__tags" :id="word.id"><div @click="showTooltip(word.id)" style="display:inline;">{{word.word}}</div><a @click="remove(word.id)">&times;</a></span>
                    <div :class="'word-tooltip__definition ' + word.id">
                        {{word.definition}}
                    </div>
                </div>
            </div>
        </div>

        <div class="form__group">
            <label for="form__control">Make sentences here</label>
            <div class="text-wrapper">
                <textarea class="form__control form__control--elastic" :class="{disabled: words.length<1}" v-model="example" :disabled="words.length<1" contenteditable @keyup="clearError"></textarea>
                <img class="loader loader--text" src="/images/Double_Ring.gif" v-show="isProcessing">
            </div>
            <small class="error__control" v-if="error.example">{{error.example[0]}}</small>
        </div>

        <div class="form__group">
            <button class="btn btn--primary" :disabled="isProcessing || error.example">Submit</button>
        </div>
    </form>
</div>
    
</template>

<script>
    import Flash from '../../helpers/flash';
    import { post, userWord } from '../../helpers/api';
    import Word from '../../models/word';
    import Definition from '../../models/definition';

    export default {
        data() {
            return {
                words: [],
                date: '',
                tz: moment.tz.guess(),
                example: "",
                error: {},
                isProcessing: false,
            };
        },
        methods: {
            wordByDate() {
                this.isProcessing = true;
                this.words = [];
                userWord('/api/userword', this.$data)
                    .then((res) => {
                        res.data.userwords.forEach(word => {
                            this.words.push({
                                'id':word.id,
                                'word':word.word,
                                'definition':word.definition.definition,
                            });
                        });
                        this.isProcessing = false;
                    })
                    .catch((err) => {
                        console.log(err.response);
                        this.isProcessing = false;
                    });
            },
            calendarClick(e) {
                e.preventDefault();
                $('#dateInput').focus().click();
            },
            remove(id) {
                $('#' + id).fadeOut();
                setTimeout(() => {
                    this.words = this.words.filter(word => {
                        return word.id != id;
                    });
                }, 500);
            },
            showTooltip(id) {
                $(`.word-tooltip__definition.${id}`).fadeIn();
                setTimeout(() => {
                    $(`.word-tooltip__definition.${id}`).fadeOut();
                }, 5000);
            },
            compose() {
                this.error = {};
                this.isProcessing = true;
                post('/api/example/save', this.$data)
                    .then((res) => {
                        Flash.setSuccess("Example Composed!");
                        this.$router.push({path:'/examples'});
                        this.isProcessing = false;
                    })
                    .catch((err) => {
                        Flash.setError("Oops! Something went wrong");
                        this.error = err.response.data;
                        this.isProcessing = false;
                    });
            },
            clearError() {
                if(this.example.length>0) {
                    this.error = {};
                }
            }
        },
        mounted() {
            
        }
    }
</script>