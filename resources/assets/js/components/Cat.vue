<template>
    <figure class="cat">
        <img :src="cat.url" :alt="cat.id" class="img-responsive img-thumbnail"
             onerror="this.src='pics/broken.png';"
        >
        <h3 v-if="cat.rank" v-text="cat.rank"/>
    </figure>
</template>

<script>
    export default {
        props: {
            cat: {}
        },
    }

</script>

<style lang="scss">
    .vote {
        .cat {
            margin: 10px 0;
            cursor: pointer;

            img {
                max-height: 300px;
                &:hover {
                    border: black solid 2px;
                }
            }
        }
    }
    .ranked {
        .cat {
            display: inline-block;
            position: relative;

            img {
                width: 100%;
            }

            h3 {
                display: none;
            }
        }
        &.top5 {
            .cat {
                margin-bottom: 10px;
                width: calc(25% - 10px);
                h3 {
                    display: block;
                    color: whitesmoke;
                    text-shadow: -1px 0 black, 0 1px black, 1px 0 black, 0 -1px black;
                    position: absolute;
                    bottom: 0;
                    left: 14px;
                }
            }
            @media screen and (max-width: 75em) {
                .cat {
                    width: calc(33% - 6px);
                }
            }
            @media screen and (max-width: 50em) {
                .cat {
                    width: calc(50% - 6px);
                }
            }
        }
        &.other {
            .cat {
                margin-bottom: 8px;
                width: calc(12.5% - 8px);
            }
            @media screen and (max-width: 75em) {
                .cat {
                    width: calc(16.5% - 4px);
                }
            }
            @media screen and (max-width: 50em) {
                .cat {
                    width: calc(25% - 4px);
                }
            }
        }

    }
</style>