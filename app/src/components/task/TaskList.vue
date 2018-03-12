<template>
    <div>
        <v-toolbar
            class="search-toolbar"
            color="white"
            dense
        >
            <v-text-field v-model="search" prepend-icon="search" hide-details single-line></v-text-field>
        </v-toolbar>

        <v-data-table
            class="users-data-table elevation-4"
            :headers="headers"
            :items="items"
            hide-actions
            item-key="id"
        >
            <template slot="items" scope="props">
                <tr @click="props.expanded = !props.expanded">
                    <td>{{ props.item.title }}</td>
                    <td class="text-xs-right">{{ props.item.description }}</td>
                    <td class="text-xs-right">{{ props.item.deadline_date }}</td>
                    <td class="text-xs-right">{{props.item.deadline_date_string}}</td>
                    <td class="text-xs-right">{{ props.item.created_at }}</td>
                    <td class="text-xs-right">{{ props.item.updated_at }}</td>
                    <td class="text-xs-right">{{ props.item.deleted_at }}</td>
                    <td class="text-xs-right">{{ props.item.finished_at }}</td>
                </tr>
            </template>
            <template slot="expand" scope="props">
                <v-card flat>
                    <form class="form-user">
                        <v-text-field
                            v-model="props.item.title"
                            label="TITLE"
                            :counter="10"
                            :error-messages="errors.collect('title')"
                            v-validate="'required|min:3|max:100'"
                            data-vv-name="title"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="props.item.description"
                            label="Description"
                            :counter="100"
                            :error-messages="errors.collect('description')"
                            v-validate="'required|min:3|max:100'"
                            data-vv-name="description"
                            required
                        ></v-text-field>
                        <v-menu
                            lazy
                            :close-on-content-click="false"
                            v-model="menu1"
                            transition="scale-transition"
                            offset-y
                            full-width
                            :nudge-right="40"
                            max-width="290px"
                            min-width="290px"
                        >
                            <v-text-field
                                :error-messages="errors.collect('props.item.deadline_date_string')"
                                v-validate="'required'"
                                data-vv-name="validade"
                                day-format="Date::toLocaleDateString('pt-BR',{ day: 'numeric' })"
                                required
                                slot="activator"
                                label="Deadline date"
                                v-model="props.item.deadline_date_string"
                                prepend-icon="event"
                                readonly
                            ></v-text-field>
                            <v-date-picker
                                v-model="validadeFormatted"
                                @change="props.item.deadline_date_string = formatDate(validadeFormatted)"
                                @input="props.item.deadline_date_string = formatDate($event)"
                                no-title scrollable actions>
                                <template slot-scope="{ save, cancel }">
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn flat color="primary" @click="cancel">Cancel</v-btn>
                                        <v-btn flat color="primary" @click="save">OK</v-btn>
                                    </v-card-actions>
                                </template>
                            </v-date-picker>
                        </v-menu>
                        <v-btn color="primary" @click="updateUser(props.item)">Update</v-btn>
                        <v-btn color="red" style='color: white' @click="deleteUser(props.item)">Delete</v-btn>
                    </form>
                </v-card>
            </template>
        </v-data-table>

        <v-dialog v-model="dialog2" max-width="500px">
            <v-card>
                <v-card-title>
                    Cadastro de novo usuário
                </v-card-title>
                <v-card-text>
                    <form class="form-user">
                        <v-text-field
                            v-model="rfidform"
                            label="RFID"
                            :counter="10"
                            :error-messages="errors.collect('rfidform')"
                            v-validate="'required|max:10'"
                            data-vv-name="rfidform"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="nameform"
                            label="Nome do condutor"
                            :counter="100"
                            :error-messages="errors.collect('nameform')"
                            v-validate="'required|max:100|min:4'"
                            data-vv-name="nameform"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="placaform"
                            label="Placa do veículo"
                            :counter="7"
                            :error-messages="errors.collect('placaform')"
                            v-validate="'required|max:7|min:7'"
                            data-vv-name="placaform"
                            required
                        ></v-text-field>
                        <v-menu
                                lazy
                                :close-on-content-click="false"
                                v-model="menu"
                                transition="scale-transition"
                                offset-y
                                full-width
                                :nudge-right="40"
                                max-width="290px"
                                min-width="290px"
                        >
                            <v-text-field
                                :error-messages="errors.collect('validadeform')"
                                v-validate="'required'"
                                data-vv-name="validadeform"
                                day-format="Date::toLocaleDateString('pt-BR',{ day: 'numeric' })"
                                required
                                slot="activator"
                                label="Válidade até"
                                v-model="validadeformFormatted"
                                prepend-icon="event"
                                readonly
                                @blur="validadeform = parseDate(validadeformFormatted)"
                            ></v-text-field>
                            <v-date-picker v-model="validadeform" @input="validadeformFormatted = formatDate($event)" no-title scrollable actions>
                                <template slot-scope="{ save, cancel }">
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn flat color="primary" @click="cancel">Cancelar</v-btn>
                                        <v-btn flat color="primary" @click="save">OK</v-btn>
                                    </v-card-actions>
                                </template>
                            </v-date-picker>
                        </v-menu>
                        <v-text-field
                            v-model="cpfform"
                            label="CPF"
                            :counter="11"
                            :error-messages="errors.collect('cpfform')"
                            v-validate="'min:11|max:11'"
                            data-vv-name="cpfform"
                        ></v-text-field>
                        <v-text-field
                            v-model="empresaform"
                            label="Empresa"
                            :counter="100"
                            :error-messages="errors.collect('empresaform')"
                            v-validate="'max:100|min:3'"
                            data-vv-name="empresaform"
                        ></v-text-field>
                    </form>
                </v-card-text>
                <v-card-actions>
                    <v-btn :disabled="errors.any()" color="primary" @click="createUser">Salvar</v-btn>
                    <v-btn color="warning" @click="clearForm">Limpar campos</v-btn>
                    <v-btn color="primary" flat @click.stop="dialog2=false">Cancelar</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-btn
            class="fab-sync"
            dark
            fab
            @click.stop="dialog2 = true"
        >
            <v-icon class='white--text'>person_add</v-icon>
        </v-btn>
    </div>
</template>

<script>
    export default {
        $validates: true,
        data () {
            return {
                search: "",
                dialog2: false,
                menu: false,
                menu1: false,
                name: '',
                rfid: '',
                cpf: '',
                placa: '',
                empresa: '',
                validade: null,
                validadeFormatted: null,
                nameform: '',
                rfidform: '',
                cpfform: '',
                placaform: '',
                empresaform: '',
                validadeform: null,
                validadeformFormatted: null,
                headers: [
                    { text: 'RFID', value: 'rfid', align: 'left', sortable: false },
                    { text: 'Nome', value: 'name' },
                    { text: 'Placa', value: 'placa' },
                    { text: 'Válido até', value: 'validade' },
                    { text: 'CPF', value: 'cpf' },
                    { text: 'Empresa', value: 'empresa' }
                ],
                items: [],
                allItems: []
            }
        },
        mounted(){
            this.$http.get('task/get')
                .then(response => {
                    return response.json()
                }, error => {
                    alert(JSON.stringify(error))
                }).then(data => {
                this.allItems = data;
                this.items = data;
            })
        },
        watch: {
            search (query, queryOld) {
                if(query.length) {
                    this.searchUsers()
                }else{
                    this.items = this.allItems
                }
            }
        },
        methods: {
            searchUsers(){
                this.items = this.allItems.filter(user => user.name.toLowerCase().includes(this.search.toLowerCase()))
            },
            formatDate (date) {
                console.log('date')
                console.log(date)
                if (!date) {
                    return null
                }
                const [year, month, day] = date.split('-')
                console.log("`${day}/${month}/${year}`")
                console.log(`${day}/${month}/${year}`)
                return `${day}/${month}/${year}`
            },
            parseDate (date) {
                if (!date) {
                    return null
                }
                const [month, day, year] = date.split('/')
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
            },
            createUser () {
                this.$validator.validateAll()
                if(!this.errors.any()){
                    this.$http.post('task/create', { name: this.nameform, rfid: this.rfidform, cpf: this.cpfform, placa: this.placaform, empresa: this.empresaform, date: this.validadeformFormatted})
                        .then(response => {
                            this.dialog2 = false
                            this.getUsers()
                            return response.json()
                        }, error => {
                            alert("RFID já cadastrado!")
                        })
                        .then(data => {})
                }
            },
            deleteUser (user) {
                console.log(user)
                this.$http.post('task/delete', { id: user.id })
                    .then(response => {
                        this.getUsers()
                        return response.json()
                    }, error => {
                        alert(JSON.stringify(error))
                    })
                    .then(data => {})
            },
            updateUser (user) {
                if(!this.errors.any()){
                    console.log('user to update')
                    console.log(user.date)
                    this.$http.put('task/update', user)
                        .then(response => {
                            //this.dialog2 = false
                            this.getUsers()
                            return response.json()
                        }, error => {
                            alert(JSON.stringify(error))
                        })
                        .then(data => {})
                }
            },
            getUsers(){
                this.$http.get('task/get')
                    .then(response => {
                        return response.json()
                    }, error => {
                        alert(JSON.stringify(error))
                    }).then(data => {
                    this.items = data;
                })
            },
            clear () {
                this.name = ''
                this.rfid = ''
                this.cpf = ''
                this.placa = ''
                this.empresa = ''
                this.validade = null
                this.$validator.reset()
            },
            clearForm () {
                this.nameform = ''
                this.rfidform = ''
                this.cpfform = ''
                this.placaform = ''
                this.empresaform = ''
                this.validadeform = null
                this.$validator.reset()
            }
        }
    }
</script>

<style lang="stylus" scoped>
    .fab-sync
        position fixed
        bottom 75px
        right 15px

    .search-toolbar
        z-index 1
        position fixed
        width 90%
        top 80px
        left 5%
        margin 0px

    .users-data-table
        z-index 0
        position absolute
        width 90%
        top 145px
        left 5%
        margin-bottom 70px

    .form-user
        margin 10px 30px 30px 30px
</style>