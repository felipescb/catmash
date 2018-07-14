<template>
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1 text-center">
                <Loader v-if="loading"/>
                <div class="row" v-if="topRankedMeals.length">
                    <h2>The top 5</h2>
                    <div class="ranked top5">
                        <Meal v-for="meal in topRankedMeals" :key="meal.id" :meal="meal"/>
                    </div>
                </div>
                <div class="row" v-if="otherRankedMeals.length">
                    <h2>The others</h2>
                    <div class="ranked other">
                        <Meal v-for="meal in otherRankedMeals" :key="meal.id" :meal="meal"/>
                    </div>
                </div>
                <div class="row not" v-if="notRankedMeals.length">
                    <h2>Not ranked yet</h2>
                    <Meal v-for="meal in notRankedMeals" :key="meal.id" :meal="meal"/>
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
                topRankedMeals: {},
                otherRankedMeals: {},
                notRankedMeals: {},
                loading: true,
            }
        },
        mounted () {
            this.axios.get(window.location).then(res => {
                this.topRankedMeals = res.data.topRankedMeals;
                this.otherRankedMeals = res.data.otherRankedMeals;
//                this.notRankedMeals = res.data.notRankedMeals;
                this.loading = false;
            });
        },
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
