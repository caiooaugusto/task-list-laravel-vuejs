<template>
    <router-view></router-view>
    <!--<home></home>-->
    <!-- if !authenticated then <login></login> -->
</template>

<script>
    import Main from './components/home/Main.vue'
    
    export default {
        components: {
            Main: Main
        },
        data () {
            return {}
        }
    }
</script>

<style lang="stylus">  
    @import '../node_modules/vuetify/src/stylus/settings/_colors'
    $theme := {
        primary: $tasklist.base
        accent: $tasklist.lighten-1
        secondary: $tasklist.darken-1
        info: $tasklist.accent-1
        warning: $tasklist.accent-1
        error: $tasklist.accent-1
        success: $tasklist.accent-1
    }
    @import '../node_modules/vuetify/src/stylus/main'
</style>