<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <div class="row" v-if="topRankedCats.length">
                    <h2>The top 5</h2>
                    <div class="ranked top5">
                        <Cat v-for="cat in topRankedCats" :key="cat.id" :cat="cat"/>
                    </div>
                </div>
                <div class="row" v-if="otherRankedCats.length">
                    <h2>The others</h2>
                    <div class="ranked other">
                        <Cat v-for="cat in otherRankedCats" :key="cat.id" :cat="cat"/>
                    </div>
                </div>
                <div class="row not" v-if="notRankedCats.length">
                    <h2>Not ranked yet</h2>
                    <Cat v-for="cat in notRankedCats" :key="cat.id" :cat="cat"/>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    import Cat from './Cat.vue';

    export default {
        components: {
            Cat,
        },

        data () {
            return {
                topRankedCats: {},
                otherRankedCats: {},
                notRankedCats: {},
            }
        },
        mounted () {
            this.axios.get(window.location).then(res => {
                this.topRankedCats = res.data.topRankedCats;
                this.otherRankedCats = res.data.otherRankedCats;
//                this.notRankedCats = res.data.notRankedCats;
            });
        },

        created () {

        },

        methods: {}
    }
</script>

<style lang="scss">
    .ranked {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        align-items: center;
    }
</style>
