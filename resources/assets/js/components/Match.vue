<template>
    <div class="container" @keyup.left="vote(carts[0].id)" @keyup.right="vote(carts[1].id)">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <Cat class="col-md-6" v-for="cat in cats" :key="cat.id" :cat="cat" @click.native="vote(cat)"/>
            </div>
        </div>
    </div>
</template>

<script>
    import Cat from './Cat.vue';

    export default {
        components: {
            Cat
        },

        data () {
            return {
                cats: {}
            }
        },
        mounted () {
            this.axios.get(window.location).then(res => {
                this.cats = res.data;
            });
        },

        created () {
            window.addEventListener('keyup', keyUp => {
                if (keyUp.keyCode === 37) {
                    return this.vote(this.cats[0])
                }
                if (keyUp.keyCode === 39) {
                    return this.vote(this.cats[1])
                }
            })
        },

        methods: {
            vote (winner) {
                this.axios.post('/matches', {
                    winner: winner.id,
                    looser: this.cats.find(cat => cat.id !== winner.id).id,
                }).then(res => {
                    this.cats = res.data;
                })
            }
        }
    }
</script>
