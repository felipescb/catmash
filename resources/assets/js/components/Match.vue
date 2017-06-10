<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <Loader v-if="loading"/>
                <div v-else>
                    <div class="row vote">
                        <Cat class="col-md-6 text-center" v-for="cat in cats" :key="cat.id" :cat="cat"
                             @click.native="vote(cat)"
                        />
                    </div>
                    <div class="row text-center" style="margin-top: 20px">
                        <button class="btn btn-default" @click="get">No one!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Cat from './Cat.vue';
    import Loader from './Loader.vue';

    export default {
        components: {
            Cat,
            Loader
        },

        data () {
            return {
                cats: {},
                loading: true
            }
        },
        mounted () {
            this.get()
        },

        created () {
            window.addEventListener('keyup', keyUp => {
                if (keyUp.keyCode === 37) {
                    return this.vote(this.cats[0])
                }
                if (keyUp.keyCode === 39) {
                    return this.vote(this.cats[1])
                }
                if (keyUp.keyCode === 38 || keyUp.keyCode === 40) {
                    return this.get()
                }
            })
        },

        methods: {
            vote (winner) {
                this.loading = true;
                this.axios.post('/matches', {
                    winner: winner.id,
                    looser: this.cats.find(cat => cat.id !== winner.id).id,
                }).then(res => {
                    this.cats = res.data;
                    this.loading = false;
                })
            },
            get () {
                this.loading = true;
                this.axios.get(window.location).then(res => {
                    this.cats = res.data;
                    this.loading = false;
                });
            }
        }
    }
</script>
