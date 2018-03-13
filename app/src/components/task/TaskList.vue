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
                <tr
                    @click="props.expanded = !props.expanded"
                    v-if="props.item.status === true"
                    style="color: forestgreen !important"
                >
                    <td>{{ props.item.title }}</td>
                    <td class="text-xs-right">{{ props.item.description }}</td>
                    <td class="text-xs-right">{{props.item.deadline_date_string}}</td>
                    <td class="text-xs-right">{{ props.item.created_at }}</td>
                    <td class="text-xs-right">{{ props.item.updated_at }}</td>
                    <td class="text-xs-right">{{ props.item.deleted_at }}</td>
                    <td class="text-xs-right">
                        <v-checkbox
                            v-model="props.item.status"
                            :value="props.item.status"
                            type="checkbox"
                            :disabled="true"
                        ></v-checkbox>
                    </td>
                </tr>
                <tr
                        @click="props.expanded = !props.expanded"
                        v-else
                >
                    <td>{{ props.item.title }}</td>
                    <td class="text-xs-right">{{ props.item.description }}</td>
                    <td class="text-xs-right">{{props.item.deadline_date_string}}</td>
                    <td class="text-xs-right">{{ props.item.created_at }}</td>
                    <td class="text-xs-right">{{ props.item.updated_at }}</td>
                    <td class="text-xs-right">{{ props.item.deleted_at }}</td>
                    <td class="text-xs-right">
                        <v-checkbox
                                v-model="props.item.status"
                                :value="props.item.status"
                                type="checkbox"
                                :disabled="true"
                        ></v-checkbox>
                    </td>
                </tr>
            </template>
            <template slot="expand" scope="props">
                <v-card flat>
                    <form class="form-user">
                        <v-text-field
                            v-model="props.item.title"
                            label="Title"
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
                            v-model="menuDeadlineDate"
                            transition="scale-transition"
                            offset-y
                            full-width
                            :nudge-right="40"
                            max-width="290px"
                            min-width="290px"
                        >
                            <v-text-field
                                day-format="Date::toLocaleDateString('pt-BR',{ day: 'numeric' })"
                                slot="activator"
                                label="Deadline date"
                                v-model="props.item.deadline_date_string"
                                prepend-icon="event"
                                readonly
                            ></v-text-field>
                            <v-date-picker
                                v-model="deadline_date_form_formatted"
                                @change="props.item.deadline_date_string = formatDate(deadline_date_form_formatted)"
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
                        <v-checkbox
                            v-model="props.item.status"
                            :value="props.item.status"
                            label="Finished?"
                            type="checkbox"
                        ></v-checkbox>
                        <v-btn color="primary" @click="updateTask(props.item)">Update</v-btn>
                        <v-btn color="red" style='color: white' @click="deleteTask(props.item)">Delete</v-btn>
                    </form>
                </v-card>
            </template>
        </v-data-table>

        <v-dialog v-model="dialog2" max-width="500px">
            <v-card>
                <v-card-title>
                    New task
                </v-card-title>
                <v-card-text>
                    <form class="form-user">
                        <v-text-field
                            v-model="title_form"
                            label="Title"
                            :counter="100"
                            :error-messages="errors.collect('title_form')"
                            v-validate="'required|min:3|max:100'"
                            data-vv-name="title_form"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="description_form"
                            label="Description"
                            :counter="100"
                            :error-messages="errors.collect('description_form')"
                            v-validate="'required|max:100|min:3'"
                            data-vv-name="description_form"
                            required
                        ></v-text-field>
                        <v-menu
                            lazy
                            :close-on-content-click="false"
                            v-model="menuDeadlineDateForm"
                            transition="scale-transition"
                            offset-y
                            full-width
                            :nudge-right="40"
                            max-width="290px"
                            min-width="290px"
                        >
                            <v-text-field
                                :error-messages="errors.collect('deadline_date_string_form')"
                                data-vv-name="deadline_date_string_form"
                                day-format="Date::toLocaleDateString('pt-BR',{ day: 'numeric' })"
                                slot="activator"
                                label="Deadline date"
                                v-model="deadline_date_string_form_formatted"
                                prepend-icon="event"
                                readonly
                                @blur="deadline_date_string_form = parseDate(deadline_date_string_form_formatted)"
                            ></v-text-field>
                            <v-date-picker v-model="deadline_date_string_form" @input="deadline_date_string_form_formatted = formatDate($event)" no-title scrollable actions>
                                <template slot-scope="{ save, cancel }">
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                        <v-btn flat color="primary" @click="cancel">Cancel</v-btn>
                                        <v-btn flat color="primary" @click="save">OK</v-btn>
                                    </v-card-actions>
                                </template>
                            </v-date-picker>
                        </v-menu>
                        <v-checkbox
                            v-model="status_form"
                            :value="status_form"
                            label="Finished?"
                            type="checkbox"
                        ></v-checkbox>
                    </form>
                </v-card-text>
                <v-card-actions>
                    <v-btn :disabled="errors.any()" color="primary" @click="createTask">Salvar</v-btn>
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
                menuDeadlineDate: false,
                menuDeadlineDateForm: false,
                items: [],
                allItems: [],
                title_form: '',
                description_form: '',
                deadline_date_string_form: null,
                deadline_date_string_form_formatted: null,
                status_form: false,
                headers: [
                    { text: 'Title', value: 'title', align: 'left' },
                    { text: 'Description', value: 'description' },
                    { text: 'Deadline date', value: 'deadline_date_string' },
                    { text: 'Created at', value: 'created_at' },
                    { text: 'Updated at', value: 'updated_at' },
                    { text: 'Deleted at', value: 'deleted_at' },
                    { text: 'Finished?', value: 'status' },
                ]
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
                    this.searchTasks()
                }else{
                    this.items = this.allItems
                }
            }
        },
        methods: {
            searchTasks(){
                this.items = this.allItems.filter(task => task.title.toLowerCase().includes(this.search.toLowerCase()))
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
            createTask () {
                this.$validator.validateAll()
                if(!this.errors.any()){
                    this.$http.post('task/create', { title: this.title_form, description: this.description_form, deadline_date_string: this.deadline_date_string_form_formatted, status: this.status_form})
                        .then(response => {
                            this.dialog2 = false
                            this.getTasks()
                            return response.json()
                        }, error => {
                            alert("Oops, try again!")
                        })
                        .then(data => {})
                }
            },
            deleteTask (task) {
                this.$http.post('task/delete', { id: task.id })
                    .then(response => {
                        this.getTasks()
                        return response.json()
                    }, error => {
                        alert(JSON.stringify(error))
                    })
                    .then(data => {})
            },
            updateTask (task) {
                console.log('task')
                console.log(task)

                if(!this.errors.any()){
                    this.$http.put('task/update', task)
                        .then(response => {
                            this.getTasks()
                            return response.json()
                        }, error => {
                            alert(JSON.stringify(error))
                        })
                        .then(data => {})
                }
            },
            getTasks(){
                this.$http.get('task/get')
                    .then(response => {
                        return response.json()
                    }, error => {
                        alert(JSON.stringify(error))
                    }).then(data => {
                    this.items = data;
                })
            },
            clearForm () {
                this.title_form = ''
                this.description_form = ''
                this.deadline_date_string_form = null
                this.deadline_date_string_form_formatted = null
                this.status_form = false
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

    .finished-task
        color #58ff68 !important
</style>