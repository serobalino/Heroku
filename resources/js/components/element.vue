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
                <i class="fab fa-github"></i>
                Ver en GitHub
            </a>
            <button class="btn btn-warning btn-sm" v-on:click="verDev(item)">
                <i class="fas fa-info"></i>
                Ver deploy
            </button>
        </div>
        <div class="mb-1 mt-2" v-if="item.artefactos.total_count">
            <p class="m-0 row border-bottom border-primary mb-2">COMPILACIONES</p>
            <div class="row">
                <div v-for="item2 in item.artefactos.artifacts" :key="item2.id" class="col">
                    <button class="btn btn-info btn-sm "
                            type="button"
                            v-on:click="descargar(item2)"
                            :disabled="load[item2.id]"
                    >
                        <i class="fas fa-circle-notch fa-spin" v-if="load[item2.id]"></i>
                        {{ item2.name }} ({{ item2.size_in_mbytes }}mb)
                    </button>
                    <br>
                    <small>Expira {{ item2.expires_at }}</small>
                </div>
            </div>
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
    data: () => ({
        load: {}
    }),
    methods: {
        verDev: function (item) {
            this.$emit('input', item);
        },
        descargar: function (item) {
            this.$set(this.load, item.id, true);
            axios({
                url: item.archive_download_url,
                method: 'GET',
                responseType: 'blob',
                headers: {'Authorization': `token ${item.sha4}`},
            }).then((response) => {
                const url = window.URL.createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `${item.name}.zip`); //or any other extension
                document.body.appendChild(link);
                link.click();
                this.$set(this.load, item.id, false);
            }).catch(() => {
                this.$set(this.load, item.id, false);
            });
        }
    }
}
</script>

<style scoped>
.avatar {
    max-height: 40px;
}

span.sm-text {
    white-space: nowrap;
}
</style>
