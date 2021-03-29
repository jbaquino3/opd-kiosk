<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <h1 class="m-0 text-dark">Pediatrics</h1>
                </div>
                <div v-show="loadingDepts">
                    <h3><i class="fa fa-spinner fa-spin"></i> Loading Departments...</h3>
                </div>
                <div class="row">
                    <div v-for="im in im_departments" :key="im.id">
                        <button class="btn no-border animate" @click="generate_queue(im.tscode)">
                            <div class="card fixed-card">
                                <img :src="'/images/' + im.tsid + '/' + im.tscode + '.png'">
                                <div class="card-body">
                                    <h4>{{ im.tsname }}</h4>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <!-- .content-header -->

        <!-- queue modal -->
        <div class="modal fade" id="mod_queue" role="dialog" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <toggle-button @change="toggleTeleco" :sync="true" v-model="teleconsultation" class="float-right mb-2" :labels="{checked: 'Teleconsultation', unchecked: 'Regular'}" :width="200" :height="35" :font-size="20"/>
                    </div>
                    <div class="modal-body">
                        <!-- print this -->
                        <div id="print_section" class="container text-center border center mx-auto">
                            <h1 class="display-1 m-0"><b style="font-size: 1.4em !important;">{{ this.queue }}</b></h1>
                            <h4 class="department">
                                <b style="font-size: 1.4em;">{{ this.department }}</b>
                            </h4>
                            <small class="form-text text-muted" style="font-size: 1.2em;">{{ this.date_now | standard_datetime }}</small>
                            <div v-if="this.pat_credentials.priority == 0" >
                                <small class="form-text text-muted" style="font-size: 1.2em;">{{ this.fullname }} {{ this.direction }}</small>                        
                            </div>
                            <div v-else>
                                <small class="form-text text-muted" style="font-size: 1.2em;">{{ this.fullname }} (Priority) {{ this.direction }}</small>
                            </div>
                            
                        </div>
                        <!-- print this -->
                        <div class="accordion" id="opd_accordion">
                            <div class="card">
                                <div class="card-header" id="ch-1">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#opd_config">
                                            Click here to add Patient Credentials <i class="fa fa-caret-down"></i>
                                        </button>
                                    </h2>
                                </div>
                                <div id="opd_config" class="collapse" data-parent="#opd_accordion">
                                    <div class="card-body text-center mx-auto">
                                        <b-form-group label="Patient Priority (Senior Priority is Automatic)">
                                            <b-form-radio-group
                                                id="btn-radios-pattype"
                                                v-model="opd_print.pat_type"
                                                :options="pat_types"
                                                buttons
                                                size="lg"
                                                name="radios-btn-default"
                                            ></b-form-radio-group>
                                        </b-form-group>
                                        <b-form-group label="Does the Patient have any PHIC?">
                                            <b-form-radio-group
                                                id="btn-radios-phic"
                                                v-model="opd_print.phic"
                                                :options="phics"
                                                buttons
                                                size="lg"
                                                name="radios-btn-default"
                                            ></b-form-radio-group>
                                        </b-form-group>
                                        <label>Referral Hospital</label>
                                        <input class="form-control form-control-lg border-primary" @focus="show" data-layout="normal" v-model="temp_refhospital" list="hospital_name" placeholder="Click me to choose hospital...">
                                        <vue-touch-keyboard :options="options" v-if="visible" :layout="layout" :cancel="hide" :accept="accept" :input="input"></vue-touch-keyboard>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" @click="cancel_queue()" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Cancel</button>
                        <button type="button" class="btn btn-primary" @click="print_queue()"><i class="fa fa-print"></i>&nbsp; Print</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- .queue modal -->

        <!-- modal loading -->
        <div class="modal" id="mod_loading" tabindex="-1" role="dialog" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body text-center">
                        <h3>Generating Queue...</h3>
                        <h1><i class="fa fa-spinner fa-spin"></i></h1>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal loading -->

        <!-- datalist for referral hospitals -->
        <datalist id="hospital_name">
            <option v-for="hos in hospitals" :key="hos.hfhudcode">{{ hos.hfhudcode }} - {{ hos.hfhudname }}</option>
        </datalist>
    </div>
</template>

<script>
    import { globalVariables } from '../app.js';

    export default {
        data() {
            return {
                im_departments: {},
                hospitals: {},

                queue: '',
                department: '',
                date_now: '',
                fullname: '',
                temp_fullname: '',
                direction: '',
                temp_direction: '',
                temp_refhospital: '',
                loadingDepts: true,

                // toggle teleconsultation
                teleconsultation: false,
                
                opd_print: new Form({
                    enccode: '',
                    pat_type: 0,
                    phic: 0,
                    ref_hospital: ''
                }),

                enccode: '',
                pat_types: [
                    { text: 'None', value: 0 },
                    { text: 'PWD', value: 2 },
                    { text: 'Inmate', value: 3 },
                    { text: 'Pregnant', value: 4 }
                ],
                phics: [
                    { text: 'No', value: 0 },
                    { text: 'Yes', value: 1 }
                ],

                pat_credentials: new Form({
                    hpercode: globalVariables.var_hpercode,
                    priority: globalVariables.var_priority,
                    tscode: '',
                    opd_type: 'APEX',
                }),

                //vue touch keyboard
                visible: false,
                layout: "compact",
                input: null,
                options: {
                    useKbEvents: false,
                    preventClickEvent: false
                }
            }
        },

        computed: {
            computedvar() {
                return globalVariables.var_hpercode, globalVariables.var_priority;
            }
        },

        methods: {
            accept(text) {
                this.hide();
            },
            show(e) {
                this.input = e.target;
                this.layout = e.target.dataset.layout;

                if(!this.visible)
                    this.visible = true
            },
            hide() {
                this.visible = false;
            },

            load_departments() {
                axios.get("api/load_departments?tsid=" + 'PEDIA').then(
                    ({data}) => {
                        this.im_departments = data;
                        this.loadingDepts = false;
                    }
                );
            },

            load_hospitals() {
                axios.get("api/load_hospitals").then(
                    ({data}) => {
                        this.hospitals = data;
                    }
                );
            },

            generate_queue(tscode) {
                $('#mod_loading').modal('show');
                this.teleconsultation = false;
                this.pat_credentials.tscode = tscode;
                this.pat_credentials.post("api/generate_queue").then(
                    ({data}) => {
                        this.opd_print.enccode = data[0]['enccode'];
                        this.queue = data[0]['filling'];
                        this.fullname = data[0]['fullname'];
                        this.date_now = data[0]['datenow'];
                        this.direction = data[0]['directions'];
                        this.department = data[0]['department'];
                        this.opd_print.pat_type = globalVariables.var_priority;
                        this.opd_print.phic = 0;
                        this.opd_print.ref_hospital = '';
                        this.load_hospitals();
                        this.hide();
                        $('#mod_loading').modal('hide');
                        $('#mod_queue').modal('show');
                    }
                );
            },

            cancel_queue() {
                axios.get("api/cancel_queue?enccode=" + this.opd_print.enccode).then(
                    ({data}) => {
                        // simple cancellation of the registered queue
                        console.log('deleted queue');
                        this.teleconsultation = false;
                    }
                );
            },

            print_queue() {
                this.opd_print.ref_hospital = (this.temp_refhospital).substr(0, (this.temp_refhospital).indexOf(' '));
                /**
                 * We first have to check if the patient type is senior or not
                 * We can do so by using an if else condition that queries 
                 * if patient type is 1 from the global variable then patient is senior
                 * If not, then we have to get the variable on this component specified by the user.
                 * 
                 * Other things we have to consider is the hospital referral or the PHIC insurance
                 * but it does not really affect initial queuing of the patient so it's fine.
                 */
                
                this.opd_print.post("api/print_queue").then(
                    ({data}) => {
                        // print queue here (javascript)
                        try {
                            this.$htmlToPaper('print_section');
                        } catch(ex) {

                        }
                        $('#mod_queue').modal('hide');
                        this.$router.push('/apex');
                    }
                );
            },

            choose_hospital() {
                $('#mod_refhospital').modal('show');
            },

            toggleTeleco() {
                $('#mod_loading').modal('show');
                if (this.teleconsultation == true) {
                    axios.get("api/set_teleconsultation?enccode=" + this.opd_print.enccode).then(
                        ({data}) => {
                            this.queue = data[0]['filling'];
                            this.temp_fullname = this.fullname;
                            this.temp_direction = this.direction;
                            this.fullname = this.temp_fullname + ' (Teleconsultation) ';
                            this.direction = "";
                            $('#mod_loading').modal('hide');
                        }
                    )
                } else {
                    axios.get("api/rev_teleconsultation?enccode=" + this.opd_print.enccode).then(
                        ({data}) => {
                            this.queue = data[0]['filling'];
                            this.fullname = this.temp_fullname;
                            this.direction = this.temp_direction;
                            $('#mod_loading').modal('hide');
                        }
                    )
                }
            }
        },

        created() {
            // The kiosk worker must load the departments first, of course. Should show depending on which component is active.
            this.load_departments();
        }
    }
</script>