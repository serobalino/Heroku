<template>
    <div class="text-center" v-if="cargando">
        <b-spinner variant="primary" type="grow" label="Cargando"></b-spinner>
    </div>
    <div v-else>
        <b-list-group >
            <item-list v-for="item in lista" :key="item.id_co" :item="item" v-on:input="verDev"/>
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
import itemList from "./element";
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
        components: {
            itemList
        },
        methods:{
            cargar:function(){
                this.cargando=true;
                servicios.consulta(location.pathname).then(response=>{
                    this.lista=response.data;
                    this.cargando=false;
                });
            },
            verDev:function(item){
                if(item.ghaction_co){
                    window.open(item.log_co, '_blank').focus();
                }else{
                    this.$bvModal.show("modal-xl");
                    this.uri=item.respuesta_co.data.output_stream_url;
                }
            }
        },
        created() {
            this.cargar();
        }
    }
</script>

<style scoped>

</style>
