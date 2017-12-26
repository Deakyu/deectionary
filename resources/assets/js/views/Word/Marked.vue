<template>
<div>
    <img class="loader" src="/images/Double_Ring.gif" v-show="isProcessing">
    <div class="datepicker" v-show="!isProcessing">
        <input id="dateInput" type="date" v-model="date" @change="wordByDate">
        <button @click="calendarClick"><i class="fa fa-calendar" aria-hidden="true"></i></button>
    </div>
    <div class="word-marked" v-for="word in words" v-show="!isProcessing" :class="{deleted: word.deleted, removed: word.removed}" v-if="!wordEmpty">
        <button class="word-marked__close-btn" @click.prevent="unbookmark(word)">&times;</button>
        <h1 class="word-marked__word" @click.prevent="goToDefinition(word.word)">{{word.word}}</h1>
        <h3 class="word-marked__definition" @click.prevent="goToDefinition(word.word)">{{word.definition}}</h3>
        <h4 class="word-marked__when" @click.prevent="goToDefinition(word.word)">{{word.created}}</h4>
    </div>
    <div class="word-empty" v-if="wordEmpty" v-show="!isProcessing">
        No words added this day. Please select another date
    </div>
</div>
    
</template>

<script>
    import {userWord, deleteWord} from '../../helpers/api';

    import Flash from '../../helpers/flash';
    import Word from '../../models/word';
    import Definition from '../../models/definition';

    export default {
        data() {
            return {
                words: [],
                date: '',
                isProcessing: false,
                wordEmpty: false,
                tz: moment.tz.guess()
            };
        },
        methods: {
            wordByDate() {
                this.isProcessing = true;
                this.words=[];
                userWord('/api/userword', this.$data)
                .then((res) => {
                    // console.log(res.data.userwords);
                    res.data.userwords.forEach((word) => {
                        this.words.push(
                            {
                                'id':word.id,
                                'word':word.word,
                                'definition':word.definition.definition,
                                'created':word.created,
                                'deleted':false,
                                'removed':false,
                            }
                        );
                    });
                    this.wordEmpty = this.words.length < 1;
                    this.isProcessing = false;
                })
                .catch((err) => {
                    // console.log(err.response);
                    this.isProcessing = false;
                });
            },
            calendarClick(e) {
                e.preventDefault();
                $('#dateInput').focus().click();
            },
            unbookmark(word) {
                word.deleted = true;
                
                deleteWord('/api/delete/' + word.word)
                .then((res) => {
                    setTimeout(()=>{
                        word.removed = true;
                    }, 300);
                })
                .catch((err) => {
                    // console.log(err.response);
                });
            },
            goToDefinition(word) {
                this.$router.push({path: '/search/' + word});
            }
        },
        mounted() {
            console.log(moment.tz.guess());
            this.words = [];
            this.isProcessing = true;
            
            // Convert javascript date to input friendly format and default it
            var temp = (new Date()).toLocaleDateString().split('/').join('-');
            var arr = temp.split('-');
            this.date = arr[2] + '-' + arr[0] + '-' + arr[1];

            userWord('/api/userword', this.$data)
            .then((res) => {
                // console.log(res.data.userwords);
                res.data.userwords.forEach((word) => {
                    this.words.push(
                        {
                            'id':word.id,
                            'word':word.word,
                            'definition':word.definition.definition,
                            'created':word.created,
                            'deleted':false,
                            'removed':false,
                        }
                    );
                });
                this.isProcessing = false;
                this.wordEmpty = this.words.length < 1;
            })
            .catch((err) => {
                // console.log(err.response);
                this.isProcessing = false;
            });
        }
    }
</script>