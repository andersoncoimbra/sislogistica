<template>
    <v-alert v-if="message" :type="typemessage" dismissible>
        {{ message }}
    </v-alert>
    <v-data-table
        :headers="headers"
        :items="transportadoras"
        :sort-by="[{ key: 'id', order: 'desc' }]"
    >
      <template v-slot:top>        
        <v-toolbar
          flat
        >
        
          <v-toolbar-title>Transportadoras</v-toolbar-title>
          <v-divider
            class="mx-4"
            inset
            vertical
          ></v-divider>
          <v-spacer></v-spacer>
          <v-dialog
            v-model="dialog"
            max-width="500px"
          >
            <template v-slot:activator="{ props }">                
              <v-btn
                class="mb-2"
                color="primary"
                dark
                v-bind="props"
              >
                Nova transportadora
              </v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="text-h5">{{ formTitle }}</span>
              </v-card-title>
  
              <v-card-text>
                <v-container>
                    <v-alert v-if="message" :type="typemessage" dismissible>
                        {{ message }}
                    </v-alert>
                    <v-form ref='form'>
                        <v-row dense>                          

                                <v-col cols="12">
                                    <v-text-field v-model="editedItem.nome" label="Nome Transportadora*" required></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="editedItem.cep" label="CEP*" required @change="apicep"></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="editedItem.endereco" label="Endereço*" required></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="editedItem.bairro" label="Bairro*" required></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="editedItem.cidade" label="Cidade*" required></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="editedItem.UF" label="Estado*" required></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="editedItem.limite_credito" label="Limite de Crédito*" required></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-text-field v-model="editedItem.data_analise_credito" label="Data de Analise de Crédito*" required></v-text-field>
                                </v-col>                           
                        </v-row>  
                    </v-form>
                </v-container>
              </v-card-text>
  
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="blue-darken-1"
                  variant="text"
                  @click="close"
                >
                  Cancelar
                </v-btn>
                <v-btn
                  v-if="editedIndex === -1"
                  color="blue-darken-1"
                  variant="text"
                  @click="savetransportadora"
                >
                Salvar
                </v-btn>
                <v-btn
                  v-else
                  color="blue-darken-1"
                  variant="text"
                  @click="updatetransportadora"
                >
                    Atualizar
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
          <v-dialog v-model="dialogDelete" max-width="700px">
            <v-card>
              <v-card-title class="text-h5">Deseja excluir a transportadora {{ editedItem.name}}?</v-card-title>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="closeDelete">Cancelar</v-btn>
                <v-btn color="red-darken-1" variant="outlined" @click="deleteItemConfirm">Excluir</v-btn>
                <v-spacer></v-spacer>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <!-- Badge status-->
        <template v-slot:item.status="{ item }">
            <v-badge
            :content="item.status"
            :color="item.status == 'aberta' ? 'primary' : item.status == 'paga' ? 'success' : 'error'"
            ></v-badge>
        </template>
      <template v-slot:item.actions="{ item }">
        <a @click="editItem(item)">
          <v-icon color="blue-darken-1">mdi-pencil</v-icon>
            Editar
        </a>
        <a @click="deleteItem(item)">
          <v-icon color="red-darken-1">mdi-delete</v-icon>
          excluir
        </a>
      </template>
      <template v-slot:no-data>
        <v-btn
          color="primary"
          @click="fetchtransportadoras"
        >
          Reset
        </v-btn>
      </template>
    </v-data-table>
</template>

<script>
import axios from 'axios';

export default {
    name: 'TableTransportadoras',
    data() {
        return {
            headers: [
                
                { title: 'id', key: 'id'},
                { title: 'nome', key: 'nome' },
                { title: 'actions', key: 'actions'},
            ],
            transportadoras: [],
            loading: true,
            dialog: false,
            dialogDelete: false,
            editedIndex: -1,
            editedItem: {
                nome: '',
                cep: '',
                endereco: '',
                bairro: '',
                cidade: '',
                UF: '',
                limite_credito: '',
                data_analise_credito: '',
                
            },
            defaultItem: {
                nome: '',
                cep: '',
                endereco: '',
                bairro: '',
                cidade: '',
                UF: '',
                limite_credito: '',
                data_analise_credito: '',
            },
            message: '',
            typemessage: '',
            assinaturas: [],
        };
    },
    computed: {
        formTitle() {
            return this.editedIndex === -1 ? 'Nova Transportadora' : 'Editar Transpotadora';
        },
    },
    mounted() {
        this.fetchtransportadoras();
    },
    methods: {
        fetchtransportadoras() {
            axios
                .get('/transportadoras')
                .then((response) => {
                    this.transportadoras = response.data;
                    this.loading = false;
                })
                .catch((error) => {
                    console.error(error);
                    this.loading = false;
                });
        },
        async savetransportadora() {
            axios
                .post('/transportadoras', {
                    ...this.editedItem,
                })
                .then((response) => {
                    this.message = `Transportadora ${response.data.nome} cadastrada com sucesso!`;
                    this.transportadoras.push(response.data);
                    this.typemessage = 'success';
                    this.dialog = false;
                    this.$refs.form.reset();
                })
                .catch((error) => {
                    console.error(error);
                    this.message = error.response.data.message;
                    this.typemessage = 'error';
                    this.loading = false;
                });
        },
        async updatetransportadora() {

            
            axios
                .put(`/transportadoras/${this.editedItem.id}`, {
                    ...this.editedItem,
                })
                .then((response) => {
                    this.message = `transportadora ${response.data.nome} atualizada com sucesso!`;
                    this.transportadoras[this.editedIndex] = response.data;
                    this.typemessage = 'success';
                    this.dialog = false;
                    this.$refs.form.reset();
                })
                .catch((error) => {
                    console.error(error);
                    this.message = error.response.data.message;
                    this.typemessage = 'error';
                    this.loading = false;
                });
        },
        editItem(item) {
            this.editedIndex = this.transportadoras.indexOf(item);
            this.editedItem = Object.assign({}, item);            
            this.dialog = true;
        },
        deleteItemConfirm() {
            let cliente = this.editedItem.nome;
            axios
                .delete(`/transportadoras/${this.editedItem.id}`)
                .then((response) => {
                    this.message = `Transpotadora ${cliente} excluída com sucesso!`;
                    this.typemessage = 'success';                                        
                })
                .catch((error) => {
                    console.error(error);
                    this.message = error.response.data.message;
                    this.typemessage = 'error';
                    this.loading = false;
                });
            this.transportadoras.splice(this.editedIndex, 1);
            this.closeDelete();
        },
        deleteItem(item) {
            this.editedIndex = this.transportadoras.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialogDelete = true;
        },
        close() {
            this.dialog = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },
        closeDelete() {
            this.dialogDelete = false;
            this.$nextTick(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            });
        },
        apicep() {
            let cep = this.editedItem.cep;
            axios
                .get(`https://viacep.com.br/ws/${cep}/json/`)
                .then((response) => {
                    this.editedItem.endereco = response.data.logradouro;
                    this.editedItem.bairro = response.data.bairro;
                    this.editedItem.cidade = response.data.localidade;
                    this.editedItem.estado = response.data.uf;
                })
                .catch((error) => {
                    console.error(error);
                    this.message = 'CEP não encontrado';
                    this.typemessage = 'error';
                    this.loading = false;
                });
        },
    },
    watch: {
        dialog(val) {
            val || this.close();
        },
        dialogDelete(val) {
            val || this.closeDelete();
        },
        message(val) {
            if (val) {
                setTimeout(() => {
                    this.message = '';
                }, 4000);
            }
        },
    },
};
</script>
