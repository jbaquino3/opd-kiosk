<template>
  <div>
      <div class="content-header">
          <div class="container-fluid">
              <h3>OPD Teleconsultation Patients ({{ this.dateNow | dateOnly }})</h3>
          </div>
          <!-- .content-header -->
          <section class="content">
              <div class="container-fluid">
                  <div class="table-responsive">
                      <div>
                        <small class="text-muted">Below are the list of patients scheduled for teleconsultations today.</small>

                        <div class="input-group float-right mb-1 col-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fa fa-search text-info"></i></span>
                            </div>
                            <input id="input_search" class="form-control form-control-sm" placeholder="Search / Filter keyword">
                        </div>

                        <button id="showSMS" hidden @click="showSMSnotifier()" class="btn btn-outline-primary btn-sm float-right">SMS NOTIFICATION <i class="fa fa-sms fa-lg"></i> </button>

                      </div>

                      <table class="table table-sm table-bordered table-hover">
                          <thead class="sticky-top">
                              <th scope="col" width="15%">Hospital Number</th>
                              <th scope="col" width="">Type</th>
                              <th scope="col">Patient Name</th>
                              <th scope="col">Department</th>
                              <th scope="col">Date encoded</th>
                          </thead>
                          <tbody>
                              <!-- <tr v-for="pat in followup" :key="pat.hpercode" @click="generate_queue(pat.hpercode, pat.tscode)"> -->
                              <tr v-for="pat in followup" :key="pat.tStamp" @click="generate_queue(pat.hpercode, pat.tscode)">
                                  <td>{{ pat.hpercode }}</td>
                                  <td>
                                      <span class="font-weight-bold text-primary"><i class="fa fa-mobile-alt fa-lg"></i></span>
                                  </td>
                                  <td>{{ pat.fullname }}</td>
                                  <td>{{ pat.tsdesc }}</td>
                                  <td>{{ pat.created_at | standard_datetime }}</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
          </section>
      </div>

      <!-- queue modal -->
        <div class="modal fade" id="mod_ffqueue" role="dialog" data-backdrop="static">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <!-- print this -->
                        <div id="print_section" class="container text-center border center mx-auto">
                            <h1 class="display-1 m-0"><b style="font-size: 1.4em !important;">{{ this.queue }}</b></h1>
                            <h4 class="department">
                                <b style="font-size: 1.4em;">{{ this.department }}</b>
                            </h4>
                            <small class="form-text text-muted" style="font-size: 1.2em;">{{ this.date_now | standard_datetime }}</small>
                            <small class="form-text text-muted" style="font-size: 1.2em;">{{ this.fullname }} {{ this.direction }}</small>
                        </div>
                        <!-- print this -->
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

        <div class="modal fade" id="mod_bydate" role="dialog" data-backdrop="static">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h4>Scheduled for: <span class="text-primary">{{this.filterByDate}}</span></h4> -->
                        <div class="row sticky-top">
                            <div class="col-md-6">
                                <div class="input-group float-right mb-1 col-md-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-calendar text-info fa-lg"></i> <span> Select Date:</span></span>
                                    </div>
                                    <input id="bydate" v-model="filterByDate" type="date" class="form-control" placeholder="Search">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group float-right mb-1 col-md-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fa fa-map-marker-alt text-info fa-lg"></i> <span> Select Department:</span></span>
                                    </div>
                                    <select v-model="sel_dept" name="" id="selected_department" class="form-control">
                                        <option value="all">ALL (OPD)</option>
                                        <option :value="dept.tscode" v-for="dept in departments" :key="dept.tscode">{{dept.tsdesc}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="row bg-dark text-light mt-2 font-weight-bold">
                            <div v-show="allDept == 1" class="col-md-2">Dept.</div>
                            <div class="col-md-1">Type</div>
                            <div class="col-md-1">Time</div>
                            <div class="col-md-1">Hosp #</div>
                            <div class="col-md-2">Name</div>
                            <div class="col-md-2">Contact #</div>
                            <div class="col-md-2">Scheduled By</div>
                        </div>
                        <!-- <div class="mt-2" v-if="filteredPatients == ''">
                            <h4 class="font-weight-bold">No scheduled patient</h4>
                        </div> -->
                        <tr v-if=" !filteredPatients " >
                            <span class=" mt-3 spinner-grow text-info" style="width: 5rem; height: 5rem"></span>
                        </tr>
                        <div class="row mt-1 table-border" v-for="pat in filteredPatients" :key="pat.hpercode" style="border-bottom: 1px solid lightgray">
                            <div v-show="allDept == 1" class="col-md-2">{{pat.tsdesc}}</div>
                            <div class="col-md-1">
                                <span class="font-weight-bold text-success" v-if="pat.isReg!=1"><i class="fa fa-user-friends"></i> F2F</span>
                                <span class="font-weight-bold text-info" v-else-if="pat.isReg==1"><i class="fa fa-mobile-alt fa-lg"></i> Tele</span>
                                <span class="font-weight-bold" v-else></span>
                            </div>
                            <div class="col-md-1">{{pat.ff_date | filterTime}}</div>
                            <div class="col-md-1" style="font-size: 70%">{{pat.hpercode}}</div>
                            <div class="col-md-2 font-weight-bold">{{pat.fullname}}</div>
                            <div class="col-md-2">
                                <span v-if="pat.pattel" class="font-weight-bold text-primary">
                                    <a :href="httpAdd+pat.enccode" target="_blank" rel="noopener noreferrer">{{pat.pattel}}</a>
                                </span>
                                <span v-else class="text-muted">Not provided</span>
                            </div>
                            <div class="col-md-2">{{pat.scheduledby}}</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="clearAll()" type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times"></i>&nbsp; Close</button>
                    </div>
                </div>
            </div>
        </div>

  </div>
</template>

<script>
import moment from 'moment'

export default {

    data() {
        return {

            httpAdd: 'http://192.168.6.172/sms?enccode=',

            followup: [],

            pat_credentials: new Form({
                hpercode: '',
                priority: 0,
                tscode: '',
                opd_type: 'FFUP'
            }),

            enccode: '',
            queue: '',
            department: '',
            date_now: '',
            fullname: '',
            direction: '',
            dateNow: '',

            opd_print: new Form({
                enccode: '',
                pat_type: 0,
                phic: 0,
                ref_hospital: ''
            }),

            filterByDate: '',
            departments: [],
            filteredPatients: [],
            sel_dept: '',
            allDept: 0,

        }
    },

    methods: {


        showSMSnotifier(){
                $('#mod_bydate').modal('show')
        },

        getFollowupPatients() {
            axios.get('api/getFollowupPatients').then(
                ({data}) => {
                    this.followup = data;
                }
            )
        },

        generate_queue(hpercode, tscode) {
            axios.get('api/checkEncounter?hpercode=' + hpercode).then(
                ({data}) => {
                    if ( data == 0) {
                        // no encounter today
                        Swal.fire({
                            title: 'Generate Queue?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Yes!'
                            }).then((result) => {
                            if (result.value) {
                                $('#mod_loading').modal('show');
                                this.pat_credentials.hpercode = hpercode;
                                this.pat_credentials.tscode = tscode;

                                this.pat_credentials.post("api/generate_queue").then(
                                    ({data}) => {
                                        this.opd_print.enccode = data[0]['enccode'];
                                        this.queue = data[0]['filling'];
                                        this.fullname = data[0]['fullname'],
                                        this.date_now = data[0]['datenow'];
                                        this.direction = data[0]['directions'];
                                        this.department = data[0]['department'];
                                        $('#mod_loading').modal('hide');
                                        $('#mod_ffqueue').modal('show');
                                    }
                                )
                            }
                        })
                    } else {
                        // patient already has encounter
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'It seems like the patient already has an encounter today!'
                        })
                    }
                }
            )
        },

        cancel_queue() {
            axios.get("api/cancel_queue?enccode=" + this.opd_print.enccode).then(
                ({data}) => {
                    // simple cancellation of the registered queue
                    console.log('deleted queue');
                }
            );
        },

        print_queue() {
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
                    $('#mod_ffqueue').modal('hide');
                    this.$router.push('/follow_up');
                }
            );
        },

        getDate() {

            var currentDate = new Date();
            // console.log(currentDate);

            var currentDateWithFormat = new Date().toJSON().slice(0,10).replace(/-/g,'/');
            // console.log(currentDateWithFormat);

            this.dateNow = currentDateWithFormat;
        },

        clearAll(){

                this.departments = '';
                this.filteredPatients = '';
                this.sel_dept = '';
        },
    },


    created() {
        this.getFollowupPatients();
        this.getDate();

        $(function(){
            $("#input_search").trigger('focus');
            let filterSearch = $("#input_search");
            filterSearch.on("keyup", function () {
                var value = $(this).val().toLowerCase();
                $("tbody tr").filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
        });
    },

    watch: {
        filterByDate(val){
            const x = this;
            axios.post('api/getByDate').then(d=>{
                console.log(d.data);
                x.sel_dept = '';
                x.departments = d.data.all_dept;

                x.filteredPatients = d.data.byDate;

                // this.filteredPatients = d.data.byDate;
            });
        },
        sel_dept(val){
            console.log(val);
            const x = this;

            if(val=='all'){
                console.log('lahat daw');
                x.allDept = 1;

                x.filteredPatients = '';

                axios.post('api/getall', {
                    date: this.filterByDate,
                }).then(d=>{
                    x.departments = d.data.all_dept;
                    x.filteredPatients = d.data.byDate;
                });
            }else{
                x.allDept = 0;

                x.filteredPatients = '';

                axios.post('api/getByDate', {
                    date: this.filterByDate,
                    dept: val
                }).then(d=>{
                    // console.log(d.data.byDate);
                    x.departments = d.data.all_dept;
                    x.filteredPatients = d.data.byDate;
                });
            }
        }
    },
    filters: {
        filterTime: function (time) {
            return moment(time).format('h:mm a');
        }
    }


}
</script>

<style>

</style>
