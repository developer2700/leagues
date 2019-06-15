<template>
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-info">
                    <div class="card-header bg-primary text-white">{{league.name}}</div>
                    <div class="card-body">

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Team Name</th>
                                <th scope="col">played</th>
                                <th scope="col">won</th>
                                <th scope="col">drawn</th>
                                <th scope="col">lost</th>
                                <th scope="col">gf</th>
                                <th scope="col">ga</th>
                                <th scope="col">gd</th>
                                <th scope="col">Points</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="leagueTeam in leagueTeams">
                                <th scope="row">{{leagueTeam.team.name}}</th>
                                <td>{{leagueTeam.played}}</td>
                                <td>{{leagueTeam.won}}</td>
                                <td>{{leagueTeam.drawn}}</td>
                                <td>{{leagueTeam.lost}}</td>
                                <td>{{leagueTeam.gf}}</td>
                                <td>{{leagueTeam.ga}}</td>
                                <td>{{leagueTeam.gd}}</td>
                                <td>{{leagueTeam.Points}}</td>
                            </tr>
                            </tbody>
                        </table>

                        <div class="card ">
                            <div class="card-header bg-primary text-white">Week {{league.nowWeek}}</div>
                            <div class="card-body">
                                <div class="row justify-content-center">

                                    <div v-if="week > 3" class="col-md-4 ">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th scope="col">Team Name</th>
                                                <th scope="col">Percent</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="leagueTeam in leagueTeams">
                                                <th scope="row">{{leagueTeam.team.name}}</th>
                                                <td>{{leagueTeam.percent}}%</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-8 bg-warning">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th scope="col">Host </th>
                                                <th scope="col">Host Goal</th>
                                                <th scope="col">Guest Goal</th>
                                                <th scope="col">Guest </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr v-for="match in weekMatches">
                                                <th scope="row">{{match.host_team.name}}</th>
                                                <td>{{match.host_result}}</td>
                                                <td>{{match.guest_result}}</td>
                                                <td scope="row">{{match.guest_team.name}}</td>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <button :disabled="eWeekLoading || playWeekLoading" class="btn btn-danger" data-toggle="modal" data-target="#editWeek" >
                                            Edit Match Results
                                            <span v-if="eWeekLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        </button>
                                        <button :disabled="pWeekLoading || playWeekLoading" @click="pWeekLoading=true;setWeek(parseInt(week) -  1 )" class="btn btn-info">
                                            Previous Week
                                            <span v-if="pWeekLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                            </button>
                                        <button :disabled="nWeekLoading || playWeekLoading" @click="nWeekLoading=true;setWeek(parseInt(week) +  1 )" class="btn btn-info">
                                            Next Week
                                            <span v-if="nWeekLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        </button>
                                        <button :disabled="playWeekLoading" @click="playWeekLoading=true;getStartWeek();" class="btn btn-primary">
                                            Play Week
                                            <span v-if="playWeekLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        </button>

                                        <button :disabled="endWeekLoading || playWeekLoading" @click="endWeekLoading=true;setWeek(38)" class="btn btn-success ">
                                            Play All Weeks
                                            <span v-if="endWeekLoading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        </button>
                                    </div>


                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade bd-example-modal-lg" id="editWeek" tabindex="-1" role="dialog" aria-labelledby="editWeekLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content bg-warning">
                    <div class="modal-header bg-danger">
                        <h5 class="modal-title" id="editWeekLabel">Edit Week {{week}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Host </th>
                                <th scope="col">Host Goal</th>
                                <th scope="col">Guest Goal</th>
                                <th scope="col">Guest </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="match in weekMatches">
                                <th scope="row">{{match.host_team.name}}</th>
                                <td><input type="number" v-model="match.host_result"></td>
                                <td><input type="number" v-model="match.guest_result"></td>
                                <td scope="row">{{match.guest_team.name}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button @click="setMatches(weekMatches)" type="button" data-dismiss="modal" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        data() {
            return {
                base: "/api/leagues/",
                leagueTeams: [],
                league: {},
                week: 1,
                weekMatches: [],
                pWeekLoading:false,
                nWeekLoading:false,
                eWeekLoading:false,
                playWeekLoading:false,
                endWeekLoading:false,
            }
        },
        methods: {

            getMatches() {
                axios.get(this.base + 'leagueTeam:league_id/' + this.league.league_id).then(res => {
                    this.leagueTeams = res.data;
                })
            },
            getLeague() {
                axios.get(this.base + 'league/1').then(res => {
                    this.league = res.data;
                    this.week = res.data.nowWeek;

                })
            },
            getWeek() {
                axios.get(this.base + 'week/' + this.week).then(res => {
                    this.weekMatches = res.data.matches;
                })
            },
            setWeek(week){
                if(week > 0 && week < 39){
                    axios.put(this.base + 'league/1',{
                        'name': this.league.name,
                        'nowWeek': week,
                    }).then(res => {
                        console.log(res);
                        this.getLeague();
                        this.pWeekLoading=false;
                        this.nWeekLoading=false;
                        this.endWeekLoading=false;
                    })
                }
            },
            setMatches(matches){
                this.eWeekLoading = true;
                var count = 0;
                var success = 0;
                matches.forEach(match=>{
                    count++;
                    axios.put(this.base + 'match/'+match.match_id,{
                        'week_id': match.week_id,
                        'host_team_id': match.host_team_id,
                        'guest_team_id': match.guest_team_id,
                        'host_result': match.host_result,
                        'guest_result': match.guest_result,
                    }).then(res => {
                        success++;
                        this.checkSetAll();
                    })
                })
            },
            checkSetAll(count,success){
                if(success === count){
                    this.getLeague();
                    this.eWeekLoading = false;
                }
            },
            getStartWeek(){
                axios.get(this.base + 'league/startWeek').then(res => {
                    this.getEndWeek();
                }).catch(err=>{
                    this.getEndWeek();
                })
            },
            getEndWeek(){
                if (this.playWeekLoading) {
                    this.getLeague();
                    this.getWeek();
                    axios.get(this.base + 'league/endWeek').then(res => {
                        this.playWeekLoading = !res.data.status;
                        this.getEndWeek();
                    }).catch(err => {
                        this.getEndWeek();
                    })
                }else{
                    setTimeout(function () {
                        this.getEndWeek();
                    },10000);
                }
            }
        },
        mounted() {
            this.getLeague();
            this.getWeek();
        },
        watch: {
            week: function () {
                this.getWeek();
            },
            league: function () {
                this.getMatches();
            }
        }
    }
</script>
