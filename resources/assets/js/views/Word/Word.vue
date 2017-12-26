<template>
<div>
    <img class="loader" src="/images/Double_Ring.gif" v-show="isProcessing">
    <div class="word" v-if="this.wordFound" v-show="!isProcessing">
        <button class="word__speak" @click="speak(word.word)">
            <i class="fa fa-volume-up"></i>
        </button>

        <!-- TODO: tooltip on like/dislike deploy not yet -->
        <button class="word__like" :class="{liked: isLiked}" @click="like">
            <div class="wrapper">
                <i class="fa fa-bookmark"></i>
                <span class="bookmark-tooltip" v-show="tooltipBookmark">
                    <div class="tooltip-wrapper">Bookmarked!</div>
                </span>
                <span class="bookmark-tooltip" v-show="tooltipUnbookmark">
                    <div class="tooltip-wrapper tooltip-wrapper--undo">Bookmark deleted!</div>
                </span>
            </div>
        </button>

        <h1 class="word__title">{{word.word}}</h1>
        <h2 class="word__pronunciation">
            {{word.pronunciation}}
        </h2>

        <div class="word__img" v-if="image">
            <a :href="imageLink"><img :src="image"></a>
        </div>

        <div v-for="definition in word.definitions" class="definition">
            <h3 class="definition__title">&nbsp;&nbsp;<i class="fa fa-angle-double-right" aria-hidden="true"></i>&nbsp;&nbsp;{{definition.definition}}</h3>
            <h4 class="definition__example" v-if="definition.hasExample()">ex) {{definition.example}}</h4>
        </div>
    </div>
</div>
    
</template>

<script>
    import {get, deleteWord, searchWord, post, userLiked} from '../../helpers/api';
    import Flash from '../../helpers/flash';
    import Word from '../../models/word';
    import Definition from '../../models/definition';
    import {searchImages} from 'pixabay-api';

    export default {
        data() {
            return {
                word: {},
                image: false,
                imageLink: false,
                isLiked: false,
                wordFound: false,
                isProcessing: false,
                tooltipBookmark: false,
                tooltipUnbookmark: false,
            };
        },
        methods: {
            like() { 
                this.isProcessing = true;
                if (this.isLiked) {
                    this.tooltipUnbookmark = true;
                    setTimeout(() => {
                        this.tooltipUnbookmark = false;
                    }, 3000);
                    deleteWord('/api/delete/' + this.word.word)
                    .then((res) => {
                        // console.log(res.data);
                    })
                    .catch((err) => {

                    });
                    this.isLiked = false;
                    this.isProcessing = false;
                } else {
                    this.tooltipBookmark = true;
                    setTimeout(() => {
                        this.tooltipBookmark = false;
                    }, 3000);
                    this.isLiked = true;
                    post('/api/save', this.word)
                    .then((res) => {
                        console.log(res.data);
                    })
                    .catch((err) => {
                        console.log(err.response);
                    });
                    this.isProcessing = false;
                }
            },
            speak(word) {
                responsiveVoice.speak(word, "US English Female");
            }
        },
        mounted() {
            this.word = {};
            this.isProcessing = true;
            searchWord(this.$route.params.query)
            .then((res) => {
                this.word = new Word(res.data);
                this.wordFound = true;
                    
                searchImages('7493731-20ab91ef9da7a087c114ee18e', this.$route.params.query)
                .then((r) => {
                    this.image = r.hits.length > 0 ? r.hits[0].webformatURL : false;
                    this.imageLink = r.hits.length > 0 ? r.hits[0].pageURL : '#';
                })
                .catch((e) => {
                    console.log(e.response);
                });
                    
                userLiked('/api/isliked', this.word)
                .then((res) => {
                    this.isLiked = res.data.userLiked ? true : false;
                    this.isProcessing = false;
                })
                .catch((err) => {
                    this.isProcessing = false;
                });
            })
            .catch((err) => {
                Flash.setError("Word not found..");
                this.wordFound = false;
                this.isProcessing = false;
            });
            // console.log("Word Mounted!");
        },
        watch: {
            '$route'(newParam, oldParam) {
                this.isProcessing = true;
                this.word = {};
                searchWord(newParam.params.query)
                .then((res) => {
                    this.word = new Word(res.data);
                    this.wordFound = true;
                    
                    searchImages('7493731-20ab91ef9da7a087c114ee18e', newParam.params.query)
                    .then((r) => {
                        this.image = r.hits.length > 0 ? r.hits[0].webformatURL : false;
                        this.imageLink = r.hits.length > 0 ? r.hits[0].pageURL : '#';
                    })
                    .catch((e) => {
                        console.log(e.response);
                    });

                    userLiked('/api/isliked', this.word)
                    .then((res) => {
                        this.isLiked = res.data.userLiked ? true : false;
                        this.isProcessing = false;
                    })
                    .catch((err) => {
                        this.isProcessing = false;
                    });
                })
                .catch((err) => {
                    Flash.setError("Word not found..");
                    this.wordFound = false;
                    this.isProcessing = false;
                });
            }
        }
    }
</script>