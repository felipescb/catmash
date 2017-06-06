<template>
    <div>
        <img :src="cat.url" :alt="cat.id">
        <span>{{cat.id}}</span>
    </div>
</template>

<script>
    export default {
        props: {
            cat: {}
        },
    }

</script>

<style lang="scss">
    img {
        height: 200px;
        width: 200px;
    }
</style>