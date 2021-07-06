<template>
    <b-list-group-item class="flex-column align-items-start"
                       :class="{'list-group-item-success':item.estado_co==='succeeded'||item.estado_co==='success','list-group-item-danger':item.estado_co==='failed' || item.estado_co==='failure'}">
        <div class="d-flex w-100 justify-content-between">
            <div class="mb-1">
                <img
                    v-if="item.github.committer"
                    :src="item.github.committer.avatar_url"
                    class="img-thumbnail avatar"
                /> {{ item.github.commit.author.name }}
            </div>
            <small>{{ item.estado_co }}</small>
        </div>
        <div class="mb-1">
            <div class="font-italic" v-if="!item.github.message">{{ item.github.commit.message }}</div>
            <br>
            <a class="btn btn-primary btn-sm"
               v-if="!item.github.message"
               :href="item.github.html_url"
               target="_blank"
            >
                Ver en GitHub
            </a>
            <button class="btn btn-warning btn-sm" v-on:click="verDev(item)">Ver deploy</button>
        </div>
        <small>{{ item.created_at }}</small>
    </b-list-group-item>
</template>

<script>
export default {
    name: "element-commit",
    props: {
        item: {
            type: Object,
            require: true,
        }
    },
    methods: {
        verDev: function (item) {
            this.$emit('input', item);
        }
    }
}
</script>

<style scoped>
.avatar{
    max-height: 40px;
}
</style>
