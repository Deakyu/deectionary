<template>
<div>
    <img class="loader" src="/images/Double_Ring.gif" v-show="isProcessing">
    
    <router-link to="/examples/create" tag="button" class="btn btn--primary btn--create" v-show="!isProcessing">
        Compose!
    </router-link>

    <div class="datepicker" v-show="!isProcessing">
        <input id="dateInput" type="date" v-model="date" @change="exampleByDate">
        <button @click="calendarClick"><i class="fa fa-calendar" aria-hidden="true"></i></button>
    </div>
    <div class="example" v-for="example in examples" v-show="!isProcessing && !exampleEmpty">
        <p class="example__example">
            {{example.example}}
        </p>
        <h4 class="example__when">{{example.created}}</h4>
    </div>

    <div class="example-empty" v-show="!isProcessing && exampleEmpty">
        Examples do not exist
    </div>
</div>
    
</template>

<script>
    import {post} from '../../helpers/api';
    import Flash from '../../helpers/flash';

    export default {
        data() {
            return {
                examples: [],
                date: '',
                isProcessing: false,
                exampleEmpty: false,
                tz: moment.tz.guess()
            };
        },
        methods: {
            exampleByDate() {
                this.isProcessing = true;
                this.words=[];
                post('/api/example-by-date', this.$data)
                .then((res) => {
                    res.data.examples.forEach((example) => {
                        this.examples.push(
                            {
                                'example': example.example,
                                'created': example.created_at,
                            }
                        );
                    });
                    this.exampleEmpty = this.examples.length < 1;
                    this.isProcessing = false;
                    if(this.exampleEmpty) Flash.setError("No Examples Found");
                })
                .catch((err) => {
                    this.isProcessing = false;
                });
            },
            calendarClick(e) {
                e.preventDefault();
                $('#dateInput').focus().click();
            },
        },
        mounted() {
            console.log(moment.tz.guess());
            this.examples = [];
            this.isProcessing = true;
            
            // Convert javascript date to input friendly format and default it
            var temp = (new Date()).toLocaleDateString().split('/').join('-');
            var arr = temp.split('-');
            this.date = arr[2] + '-' + arr[0] + '-' + arr[1];

            post('/api/examples', this.$data)
            .then((res) => {
                res.data.examples.forEach((example) => {
                    this.examples.push(
                        {
                                'example': example.example,
                                'created': example.created_at,
                        }
                    );
                });
                this.isProcessing = false;
                this.exampleEmpty = this.examples.length < 1;
                if(this.exampleEmpty) Flash.setError("No Examples Found");
            })
            .catch((err) => {
                this.isProcessing = false;
            });
        }
    }
</script>