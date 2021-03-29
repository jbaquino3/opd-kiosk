<template>
    <div>
        <div class="content-header">
            <div class="container-fluid">
                <h3>APEX Patient History</h3>
            </div>
        </div>
        <!-- .content-header -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <button class="btn btn-info info-box-icon elevation-1" @click="getPatientHistory(globalVariables.var_hpercode)">
                                <div v-if="img_user == ''">
                                    <img :src="'./images/opd_pics/noimage.jpg'" width="70" height="70">
                                </div>
                                <div v-else>
                                    <img :src="'./images/patients/'+ img_user" width="70" height="70">
                                </div>
                            </button>
                            <div class="info-box-content">
                                <span class="info-box-text">Patient Details</span>
                                <span class="info-box-number">{{ fullname }} ({{gender}} | {{age}} yrs)</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-warning elevation-1">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Patient Last Visit</span>
                                <span class="info-box-number">{{lastvisit | dateFormat}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <div class="info-box">
                            <span class="info-box-icon bg-success elevation-1">
                                <i class="fa fa-hospital"></i>
                            </span>
                            <div class="info-box-content">
                                <span class="info-box-text">Patient Last Department</span>
                                <span class="info-box-number">{{lastdept}}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-light table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Visit Date/Time</th>
                                <th scope="col">Department</th>
                                <th scope="col">Diagnosis</th>
                                <th scope="col">Discharge Status</th>
                                <th scope="col">Referred From</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="his in patient_history" :key="his.enccode">
                                <td>{{ his.opddate | dateFormat }}</td>
                                <td>{{ his.tsdesc }}</td>
                                <td>{{ his.diagfin }}</td>
                                <td>{{ his.dispdesc }}</td>
                                <td>{{ his.hfhudname }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- patient search modal -->
        <div class="modal fade" id="mod_search" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">OPD eKiosk Search Patient -</h5>
                        <button type="button" class="btn btn-sm btn-info" @click="recent_logs"><i class="fa fa-info-circle"></i>&nbsp; Recent Logs <span v-show="recentLogClick"><i class="fa fa-spinner fa-spin"></i></span></button>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-md-3"></div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Hospital Number</label>
                                        <input type="text" class="form-control text-uppercase" id="hpercode" :value="inputs['hpercode']" @focus="onInputFocus" @input="onInputChange">
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#searchParameters"><i class="fa fa-plus"></i>&nbsp;Additional Search Parameters</button>
                                            </h2>
                                        </div>
                                        <div id="searchParameters" class="collapse container">
                                            <div class="form-group">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control text-uppercase" id="lastname" :value="inputs['lastname']" @focus="onInputFocus" @input="onInputChange">
                                            </div>
                                            <div class="form-group">
                                                <label>First Name</label>
                                                <input type="text" class="form-control text-uppercase" id="firstname" :value="inputs['firstname']" @focus="onInputFocus" @input="onInputChange">
                                            </div>
                                            <div class="form-group">
                                                <label>Middle Name</label>
                                                <input type="text" class="form-control text-uppercase" id="midname" :value="inputs['midname']" @focus="onInputFocus" @input="onInputChange">
                                            </div>
                                        </div>
                                    </div>
                                    <button id="btn_search" class="btn btn-success btn-lg float-right" @click="search_patient()">Search<span v-show="searching">ing... &nbsp;<i class="fa fa-spinner fa-spin"></i></span></button>
                                    <a class="btn btn-secondary btn-lg float-right mr-1" href="/"><i class="fa fa-arrow-left"></i> &nbsp; Back </a>
                                </div>
                                <div class="col-md-3"></div>
                            </div>
                            
                        </div>
                        <hr>
                        <SimpleKeyboard
                            @onChange="onChange"
                            @onKeyPress="onKeyPress"
                            :input="inputs[inputName]"
                            :inputName="inputName"
                        />  
                    </div>
                </div>
            </div>
        </div>
        <!-- .modal -->

        <!-- searched patients -->
        <div class="modal fade" id="mod_searched" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Patients Searched</h3>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="table-responsive" style="height: 20rem;">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Hospital Number</th>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Age</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="pat in searched_patients" :key="pat.hpercode" @click="select_patient(pat.hpercode)" :class="{'highlight': (pat.hpercode == selected_patient)}">
                                            <td>{{ pat.hpercode }}</td>
                                            <td>{{ pat.fullname }}</td>
                                            <td>{{ pat.age }} yr/s</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal" @click="cancel_searchedPatient"><i class="fa fa-times"></i>&nbsp;Close</button>
                        <button v-show="log_patient" class="btn btn-success" @click="logPatient"> Log Patient</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- .modal -->

        <!-- registration modal -->
        <div class="modal fade" id="mod_registration" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                                <div>
                                    <div id="my_cam" class="mx-auto" name="my_cam" style="border: 1px solid black"></div>
                                    <button type="button" class="btn btn-lg btn-primary btn-block m-1" v-show="capPic" @click="capture_pic()"><i class="fa fa-camera"></i>&nbsp;Capture</button>
                                    <button type="button" class="btn btn-lg btn-danger btn-block m-1" v-show="takePic" @click="take_another_pic()"><i class="fa fa-camera"></i>&nbsp;Take Another Picture</button>
                                </div>
                                <h5>{{ patname }}</h5>
                                <h5>{{ pataddress }}</h5>
                                <h5>{{ patbday | dateFormat}}</h5>
                            </div>
                            <div class="col-md-3"></div>
                            <input type="hidden" name="image" class="image-tag" v-model="frm_img.img">
                            <input type="hidden" name="hospitalno" id="hospitalno">       
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" @click="register_patient">Register Patient</button>
                        <button type="button" class="btn btn-secondary" @click="registration_back" data-dismiss="modal">Back</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- .modal -->

        <!-- data privacy modal -->
        <div class="modal fade" id="mod_dataprivacy" tabindex="-1" role="dialog" aria-labelledby="dpModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dpModalTitle">Data Privacy Terms</h5>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="hosno" class="form-control form-control-lg rounded-0" name="hosno" required=""  hidden>
                            <ul class="nav nav-tabs" id="dpTabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="dp-fil" data-toggle="tab" href="#dpFil" role="tab" aria-controls="dpFil" aria-selected="true">Filipino</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="dp-eng" data-toggle="tab" href="#dpEng" role="tab" aria-controls="dpEng" aria-selected="true">English</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="tabContent">
                                <div class="tab-pane fade show active" id="dpFil" role="tabpanel" aria-labelledby="dp-fil">
                                    <br>
                                    <h6 class="text-center"><b>PAGSANG-AYON SA PAGKOLEKTA NG PERSONAL NA IMPORMASYON</b></h6>
                                    <hr>
                                    Ang inyong pribadong pagkakakilanlan ay napakahalaga sa amin at ang lahat ng nakalap na personal na impormasyon ay aming maalagaan ng mabuti kaya lahat ng pagsisikap ay aming gagawin upang maprotektahan ito.  Lahat ng mga empleyado na dadaanan ng inyong impormasyong ibinahagi sa amin ay may sapat na kaalaman hinggil sa taglay nitong likas na sensitibong detalye. Sila rin ay may sapat na kaalaman ukol  sa tamang paggamit at proteksiyon ng inyong impormasyon. Tanging ang mga kailangang impormasyon lamang ang makokolekta at maibabahagi ng may karampatang pahintulot na maibahagi ito. Ang aming pribadong panuntunan ay nasasang-ayon sa pamantayang ibinigay ng National Privacy Commission. 
                                    <br><br>
                                    Ang mga makakalap na  impormasyon ay magagamit at maibabahagi sa mga sumusunod na layunin: pagpoproseso ng  inyong pag rehistro  gamit ang aming HOMIS; pagbibigay ng karampatang pag-aalagang medikal at pagpapayo ukol sa ibang paraan ng paggagamot; panatilihin at paigtingin ang pakikipagugnayan sa inyo; mabahagian kayo ng kopya ng  mga newsletter at iba pang impormasyon na may kinalaman sa “Data Breach”; kami ay inyong mabigyan ng pahintulot na epektibong masubaybayan ang inyong pagpapagamot, at sa mga bagay na may kinalaman sa bayarin sa aming ospital; pagpoproseso ng inyong PHIC Claims o iba pang Insurance Claims; maiproseso ang inyong mga  bayarin at makakolekta ng anumang mga bayaring hindi nabayaran; makasunod  sa mga alituntunin na hinihingi ng  specialty board at makasunod sa lahat ng regulasyong legal kasama na dito ang lahat ng kautusan ng korte, mga pamantayang naayon sa batas, pag-uulat ng mga nakakahawang sakit at mga indibidwal na maaaring magbigay ng panganib sa kanyang sarili o sa iba. 
                                    <br><br>
                                    Nabasa at naintindihan  ko ang mga nasabing kondisyon kung saan ipinaliwanag ng BGHMC ang paggamit ng aking personal na impormasyon at ang kanilang hakbang na gagawin para maprotektahan ang aking impormasyon. 
                                    <br><br>
                                    Ako ay sumasang-ayon na ang BGHMC ay makakakalap, magagamit at maibabahagi ang aking personal na impormasyon at mapoprotektahan ang kompidensyal at integridad na nasasaad sa Data Privacy Manual ng Baguio General Hospital and Medical Center 
                                    <hr>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kanselahin</button>
                                        <button type="submit" class="btn btn-primary" @click="save_bioinfo()">Ako ay sumasang-ayon</button>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="dpEng" role="tabpanel" aria-labelledby="dp-eng">
                                    <br>
                                    <h6 class="text-center"><b>CONSENT PRIOR TO THE COLLECTION OF PERSONAL INFORMATION</b></h6>
                                    <hr>
                                    Your privacy is very important to us and all information collected will be handled responsibly. All effort will be made to be as open and transparent as possible with how your information is handled and protected. All staff members who come in contact with your personal information are aware of the sensitive nature of the information that you have disclosed to us. They are trained in the appropriate use and protection of your information. Only necessary or relevant information will be collected and this information will only be shared with your consent. Our privacy protocols are in accordance with the standards set by the National Privacy Commission. 
                                    <br><br>
                                    Information will be collected, used and disclosed for the following purposes: to process registration of your information to the HOMIS; assess your health concerns; provide health care;  advise you of treatment options; to establish and maintain contact with you; to send you newsletter and other information related to data breach; remind you of your medical appointment; to allow us to effectively follow up for treatment care and billing; process PHIC Claims or Insurance Claims; process payments, collect unpaid accounts; comply with hospital and department accreditation; comply with specialty board examination or certification and comply with all regulatory and legal requirements including court orders, statutory requirements to advise authorities and reporting of communicable diseases and individuals who may be in danger of harming themselves or others
                                    <br><br>
                                    By agreeing on this Patient Consent Form, you have agreed that you have given your consent to the collection, use and/or disclosure of your personal information as outlined above. I have reviewed the above conditions which that explains how your hospital will use my personal information and steps that your hospital is taking to protect my information. 
                                    <br><br>
                                    I agree that Baguio General Hospital and Medical Center can collect, use and disclose personal information about myself   and protect its confidentiality and integrity as stated in the Data Privacy Manual of Baguio General Hospital and Medical Center.  
                                    <hr>
                                    <div class="pull-right">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <button type="submit" class="btn btn-primary" @click="save_bioinfo()">I agree</button>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- .modal -->

        <!-- recent logs -->
        <div class="modal fade" id="mod_recentLogs" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Recent Logs</h3>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-info elevation-1">
                                            <i class="fa fa-calendar"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Date Now</span>
                                            <span class="info-box-number">{{ dateNow | dateFormat}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="info-box">
                                        <span class="info-box-icon bg-primary elevation-1">
                                            <i class="fa fa-users"></i>
                                        </span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">OPD Patients Today</span>
                                            <span class="info-box-number">{{ countPatients }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- .row -->
                        </div>
                        <div class="container">
                            <div class="table-responsive" style="height: 20rem;">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th scope="col">Full Name</th>
                                            <th scope="col">Dept. Code</th>
                                            <th scope="col">Queue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="logs in rec_logs" :key="logs.enccode">
                                            <td>{{ logs.patientName }}</td>
                                            <td>{{ logs.tscode }}</td>
                                            <td>{{ logs.filling }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp;Close</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- .modal -->
    </div>
</template>

<script>
    import { globalVariables } from '../app.js';
    import SimpleKeyboard from './SimpleKeyboard';

    export default {
        components: {
            SimpleKeyboard
        },
        data(){
            return {
                dateNow: '',
                countPatients: '',
                pat_history: '',
                searched_patients: {},
                patient_history: {},
                searching: false,
                selected_patient: null,
                log_patient: false,
                rec_logs: {},
                recentLogClick: false,

                // patient deets
                fullname: '',
                gender: '',
                age: '',
                lastvisit: '',
                lastdept: '',
                img_user: '',

                patname: '',
                pataddress: '',
                patbday: '',

                /* simple-keyboard declaration */
                inputs: {
                    hpercode: "",
                    lastname: "",
                    firstname: "",
                    midname: ""
                },
                inputName: "hpercode",

                frm_searchPatient: new Form({
                    hpercode: '',
                    lastname: '',
                    firstname: '',
                    midname: ''
                }),

                capPic: true,
                takePic: false,

                frm_img: new Form({
                    img: null
                }),

            }
        },

        computed: {
            computedVar() {
                return globalVariables.var_hpercode, globalVariables.var_priority, globalVariables.webcam_in;
            }
        },

        methods: {
            onChange(input) {
                this.inputs[this.inputName] = input;
            },
            onKeyPress(button) {
                console.log("button", button);
            },
            onInputChange(input) {
                this.inputs[input.target.id] = input.target.value;
            },
            onInputFocus(input) {
                this.inputName = input.target.id;
            },

            search_patient() {
                this.frm_searchPatient.hpercode = this.inputs.hpercode;
                this.frm_searchPatient.lastname = this.inputs.lastname;
                this.frm_searchPatient.firstname = this.inputs.firstname;
                this.frm_searchPatient.midname = this.inputs.midname;

                if (this.frm_searchPatient.hpercode != ''){
                    this.searching = true;
                    this.selected_patient = this.frm_searchPatient.hpercode;
                    axios.get("api/opd_bio?hpercode=" + this.frm_searchPatient.hpercode).then(
                        ({data}) => {
                            if (data == 1) {
                                try {
                                    axios.get("api/log_patient?hpercode=" + this.frm_searchPatient.hpercode).then(
                                        ({data}) => {
                                            if (data != 1) {
                                                globalVariables.var_hpercode = data[0]['hpercode'];
                                                if (data[0]['age'] >= 60) {
                                                    globalVariables.var_priority = 1;
                                                } else {
                                                    globalVariables.var_priority = 0;
                                                }
                                                this.img_user = data[0]['patimage'];
                                                this.fullname = data[0]['fullname'];
                                                this.gender = data[0]['patsex'];
                                                this.age = data[0]['age'];
                                                this.lastvisit = data[0]['opddate'];
                                                this.lastdept = data[0]['tsdesc']
                                                this.getHistory();
                                                $('#mod_search').modal('hide');
                                                $('#mod_searched').modal('hide');
                                            } else {
                                                this.searching = false;
                                                Swal.fire({
                                                    icon: 'error',
                                                    title: 'Oops...',
                                                    text: 'The patient already has an existing encounter for today!'
                                                })
                                            }
                                        }
                                    )
                                } catch(err) {}
                            } else {
                                axios.get("api/get_patient_details?hpercode=" + this.frm_searchPatient.hpercode).then(
                                    ({data}) => {
                                        $('#hospitalno').val(this.frm_searchPatient.hpercode);
                                        this.img_user = data[0]['patimage'];
                                        this.patname = data[0]['fullname'];
                                        this.pataddress = data[0]['address'];
                                        this.patbday = data[0]['patbdate'];
                                        this.mount_camera();
                                        this.searching = false;
                                        $('#mod_searched').modal('hide');
                                        $('#mod_search').modal('hide');
                                        $('#mod_registration').modal('show');
                                    }
                                );
                            }
                        }
                    );
                }

                else if (this.frm_searchPatient.hpercode == '' || this.frm_searchPatient.lastname != '' || this.frm_searchPatient.firstname != '' || this.frm_searchPatient.midname != '') {
                    this.searching = true;
                    try {
                        this.frm_searchPatient.post("api/search_patient").then(
                            ({data}) => {
                                if (data != 0) {
                                    this.searched_patients = data;
                                    this.searching = false;
                                    $('#mod_searched').modal('show');
                                } else {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Oops...',
                                        text: "Patient not found",
                                    })
                                    this.frm_searchPatient.hpercode = '';
                                    this.frm_searchPatient.lastname = '';
                                    this.frm_searchPatient.firstname = '';
                                    this.frm_searchPatient.midname = '';
                                    this.searching = false;
                                }
                            }
                        ).catch((err) => {
                            this.searching = false; 
                            console.log(err); 
                        });
                    } catch(ex) {
                        
                    }
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: "Please input search parameters",
                    })
                }
            },

            cancel_searchedPatient() {
                this.log_patient = false;
                this.selected_patient = null;
            },

            select_patient(hcode) {
                this.selected_patient = hcode;
                this.log_patient = true;
            },

            logPatient() {
                // check if patient is registered in opdbio
                axios.get("api/opd_bio?hpercode=" + this.selected_patient).then(
                    ({data}) => {
                        if (data == 1) {
                            axios.get("api/log_patient?hpercode=" + this.selected_patient).then(
                                ({data}) => {
                                    if (data != 1) {
                                        globalVariables.var_hpercode = data[0]['hpercode'];
                                        if (data[0]['age'] >= 60) {
                                            globalVariables.var_priority = 1;
                                        } else {
                                            globalVariables.var_priority = 0;
                                        }
                                        this.img_user = data[0]['patimage'];
                                        this.fullname = data[0]['fullname'];
                                        this.gender = data[0]['patsex'];
                                        this.age = data[0]['age'];
                                        this.lastvisit = data[0]['opddate'];
                                        this.lastdept = data[0]['tsdesc']
                                        this.getHistory();
                                        $('#mod_search').modal('hide');
                                        $('#mod_searched').modal('hide');
                                    } else {
                                        this.searching = false;
                                        
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'The patient already has an existing encounter for today!'
                                        })
                                    }
                                }
                            )
                        } else {
                            axios.get("api/get_patient_details?hpercode=" + this.selected_patient).then(
                                ({data}) => {
                                    $('#hospitalno').val(this.selected_patient);
                                    this.img_user = data[0]['patimage'];
                                    this.patname = data[0]['fullname'];
                                    this.pataddress = data[0]['address'];
                                    this.patbday = data[0]['patbdate'];
                                    this.mount_camera();
                                    $('#mod_searched').modal('hide');
                                    $('#mod_search').modal('hide');
                                    $('#mod_registration').modal('show');
                                }
                            );
                        }
                    }
                );
            },

            getHistory() {
                axios.get("api/get_history?hpercode=" + globalVariables.var_hpercode).then(
                    ({data}) => {
                        this.patient_history = data;
                    }
                )
            },

            mount_camera(){
                Webcam.set({
                    width: 480,
                    height: 360,
                    image_format: 'jpeg',
                    jpeg_quality: 80,
                    flip_horiz: true
                });
                Webcam.attach('#my_cam');
            },

            capture_pic() {
                Webcam.freeze();
                this.capPic = false;
                this.takePic = true;
            },

            take_another_pic(){
                Webcam.unfreeze();
                this.takePic = false;
                this.capPic = true;
            },

            registration_back() {
                /**
                 * Check WebCam.js Documentation for Webcam functions
                 */
                Webcam.reset();
                this.takePic = false;
                this.capPic = true;
                $('#mod_search').modal('show');
            },

            register_patient() {
                $('#mod_dataprivacy').modal('show');
            },

            save_bioinfo() {
                let _this = this;
                /**
                 * Webcam function that sends img binary data to PHP controller for processing.
                 * webcam_in is the global variable that checks if there is a webcam or not
                 */
                if (globalVariables.webcam_in != 0) {
                    Webcam.snap(function(data_uri) {
                        
                        axios.post('api/register_patient?imgdata=' + data_uri + '&hpercode=' + $('#hospitalno').val()).then(
                            ({data}) => {
                                $('#mod_registration').modal('hide');
                                $('#mod_dataprivacy').modal('hide');
                                Swal.fire({
                                    position: 'top-end',
                                    icon: 'success',
                                    title: 'Patient successfully registered',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                                Webcam.reset();
                                _this.logPatient();  
                            }
                        )
                    });
                    //this.logPatient();
                } else {
                    console.log($('#hospitalno').val());
                    
                    axios.post('api/register_patient_noimg?hpercode='+$('#hospitalno').val()).then(
                        ({data}) => {
                            $('#mod_registration').modal('hide');
                            $('#mod_dataprivacy').modal('hide');
                            Swal.fire({
                                position: 'top-end',
                                icon: 'success',
                                title: 'Patient successfully registered',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            _this.logPatient();
                        }
                    );
                    
                }
            },

            recent_logs() {
                this.recentLogClick = true;
                axios.get("api/get_recent_logs").then(
                    ({data}) => {
                        this.rec_logs = data;
                        axios.get("api/count_patients").then(({data}) => {this.dateNow=data[0]['dateNow']; this.countPatients=data[0]['countPatients']});
                        $('#mod_recentLogs').modal('show');
                        this.recentLogClick = false;
                    }
                );
            }
        },

        mounted() {
            $('#mod_search').modal('show');
            // $('#hpercode').trigger('focus');
        },

        created() {
            $(document).on('keypress',function(e) {
                if(e.which == 13) {
                    $('#btn_search').trigger('click');
                }
            });

            // fires if there is no webcam available
            Webcam.on('error', function(err) {
                console.log('No camera input detected!')
                globalVariables.webcam_in = 0;
            });
            Fire.$on('logPatient', () => {
                this.logPatient();
            });
        }
    }
</script>
