<template>
    <div class="text-center" v-if="cargando">
        <b-spinner variant="primary" type="grow" label="Cargando"></b-spinner>
    </div>
    <div v-else>
        <b-list-group  >
            <b-list-group-item class="flex-column align-items-start" v-for="item in lista" :key="item.id_co" :class="{'list-group-item-success':item.estado_co==='succeeded','list-group-item-danger':item.estado_co==='failed'}">
                <div class="d-flex w-100 justify-content-between">
                    <div class="mb-1" ><img v-if="item.github.committer" :src="item.github.committer.avatar_url" class="img-thumbnail avatar"/>{{item.github.commit.author.name}}</div>
                    <small>{{item.estado_co}}</small>
                </div>
                <div class="mb-1">
                    <div class="font-italic" v-if="!item.github.message" >{{item.github.commit.message}}</div>
                    <br>
                    <button class="btn btn-primary btn-sm" v-if="!item.github.message" v-on:click="verGit(item)">ver en GitHub</button>
                    <button class="btn btn-warning btn-sm" v-on:click="verDev(item)">ver deploy</button>
                </div>
                <small>{{item.created_at}}</small>
            </b-list-group-item>
        </b-list-group>
        <b-modal id="modal-xl" size="xl" title="Deploy">
            <div class="text-center" v-show="cargando">
                <b-spinner variant="primary" type="grow" label="Cargando" ></b-spinner>
            </div>
            <div class="embed-responsive embed-responsive-16by9" v-show="!cargando">
                <iframe class="embed-responsive-item" :src="uri" :onload="cargando=false"></iframe>
            </div>
        </b-modal>
    </div>

</template>

<script>
    import servicios from "../servicios";
    export default {
        name: "commits",
        data:()=>({
            cargando:true,
            descarga:true,
            lista:[],
            log:null,
            uri:null,
        }),
        methods:{
            cargar:function(){
                this.cargando=true;
                servicios.consulta(location.pathname).then(response=>{
                    this.lista=response.data;
                    this.cargando=false;
                });
            },
            verGit:function(html){
                window.open(html.github.html_url, '_blank');
            },
            verDev:function(item){
                this.$bvModal.show("modal-xl");
                // if(item.log){
                //     this.descarga=false;
                // }else{
                //     this.descarga=true;
                //     servicios.deploy(item.respuesta_co.data.output_stream_url).then(reponse=>{
                //         item.log=reponse.data;
                //         this.descarga=false;
                //     });
                // }
                // this.log=item.log;
                this.uri=item.respuesta_co.data.output_stream_url;
            }
        },
        created() {
            this.cargar();
        }
    }
</script>

<style scoped>
    .avatar{
        max-height: 40px;
    }

</style>
