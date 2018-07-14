<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <Loader v-if="loading"/>
                <div v-else>
                    <div class="row vote">
                        <Meal class="col-md-6 text-center" v-for="meal in meals" :key="meal.id" :meal="meal"
                             @click.native="vote(meal)"
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
    import Meal from './Meal.vue';
    import Loader from './Loader.vue';

    export default {
        components: {
            Meal,
            Loader
        },

        data () {
            return {
                meals: {},
                loading: true
            }
        },
        mounted () {
            this.get()
        },

        created () {
            window.addEventListener('keyup', keyUp => {
                if (keyUp.keyCode === 37) {
                    return this.vote(this.meals[0])
                }
                if (keyUp.keyCode === 39) {
                    return this.vote(this.meals[1])
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
                    looser: this.meals.find(meal => meal.id !== winner.id).id,
                }).then(res => {
                    this.meals = res.data;
                    this.loading = false;
                })
            },
            get () {
                this.loading = true;
                this.axios.get(window.location).then(res => {
                    this.meals = res.data;
                    this.loading = false;
                });
            }
        }
    }
</script>
