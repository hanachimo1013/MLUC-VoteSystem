import {createStore} from "vuex";
import { axiosClient, axiosClientVoter, axiosOrigin, axiosSanctum } from "../axios";




const adminModule = {
  state: {
    user: {
      data: {},
      token: localStorage.getItem("TOKEN"),
    },
    registeredVoters: {
      data: {}
    },
    tempStudentsRecordHold: {
      loading: false,
      data: {}
    },
  },
  getters: {},
  actions: {
    //getting registered voters
    getRegisteredVoters({commit}) {
      return axiosClient.get('/voterget_all')
        .then(({data}) => {
          commit('setDataTableRegStud', data)
          return data;
        })
    },
    //session data when logged in
    getSession() {
      return axiosClient.get('/user')
        .then(({data}) => {
          return data;
        })
    },
    //admin login
    login({commit}, userLogin) {
      return axiosSanctum.get('/sanctum/csrf-cookie')
        .then(response => {
          return axiosClient.post('/adminLogin', userLogin)
            .then(({data}) => {
              commit('setUser', data);
              return data;
            })
        });
    },
    //admin logout
    logout({commit}, userLogout) {
      return axiosClient.post('/logout', userLogout)
        .then(response => {
          commit('logout');
          return response;
        });
    },
    //making admin account
    register({commit}, userRegister) {
      return axiosClient.post('/make_admin', userRegister)
        .then(({data}) => {
          return data;
        })
    },
    //searching an id of the student
    loadStudentSearch({commit}, request) {
      const jsonSearch = JSON.stringify(request.idNum);
      return axiosClient.get('/voterinfo/' + jsonSearch)
        .then(({data}) => {
          return data;
        })
    },
    //this is being used to gather data from MIS
    getAllRecordsSource({commit}) {
      return axiosOrigin.get('/getstudentrecords')
        .then(({data}) => {
          commit('parseDataFromOrigin', data)
          return data;
        });
    },
    //sending a .csv file to the API server
    systemRecordUpdate({commit}, dataArray) {
      return axiosClient.post('/update_records', dataArray)
        .then(({data}) => {
          return data;
        });
    },
    //sending a .PDF to archives data
    systemRecordUpdate2({commit}, dataArray) {
      return axiosClient.post('/archive_data', dataArray)
        .then(({data}) => {
          return data;
        });
    },
    //geting data for update master dashboard
    getMstrDash() {
      return axiosClient.get('/mstr_dash')
        .then(({data}) => {
          return data;
        })
    },
    //campaign site data
    getCandidateInfo() {
      return axiosClient.get('/campaignSite')
        .then(({data}) => {
          return data;
        })
    },
    //getting colleges data for registration
    getCollegesData() {
      return axiosClient.get('/colleges')
        .then(({data}) => {
          return data;
        })
    },
    //saved election data
    getElectionData() {
      return axiosClient.get('/election_data')
        .then(({data}) => {
          return data;
        })
    },
    //saved positions data
    getPositionData() {
      return axiosClient.get('/get_positions')
        .then(({data}) => {
          return data;
        });
    },
    //creating election
    createElections({commit}, setElection) {
      return axiosClient.post('/create_election', setElection)
        .then(({data}) => {
          return data;
        });
    },
    //deleting election
    deleteElection({commit}, deleteElec) {
      return axiosClient.post('/delete_election', deleteElec)
        .then(({data}) => {
          return data;
        })
    },
    //creating position
    createPositionMode({commit}, setPosition) {
      return axiosClient.post('/create_position', setPosition)
        .then(({data}) => {
          return data;
        })
    },
    //deleting position
    deletePosition({commit}, deletePos) {
      return axiosClient.post('/delete_position', deletePos)
        .then(({data}) => {
          return data;
        })
    },
    //Partylist functions
    getPartylistData() {
      return axiosClient.get('/partylist_data')
        .then(({data}) => {
          return data;
        });
    },
    //creating partylist
    addPartylist({commit}, addPartylist) {
      return axiosClient.post('/create_partylist', addPartylist)
        .then(({data}) => {
          return data;
        })
    },
    //deleting partylist
    deletePartylist({commit}, deleteParty) {
      return axiosClient.post('/delete_partylist', deleteParty)
        .then(({data}) => {
          return data;
        })
    },
    //fetching candidate info
    getSavedCandidates() {
      return axiosClient.get('/get_candidates')
        .then(({data}) => {
          return data;
        })
    },
    //creating candidates
    createCandidates({commit}, addCand) {
      return axiosClient.post('/create_candidate', addCand)
        .then(({data}) => {
          return data;
        })
    },
    //deleting candidates
    deleteCandidate({commit}, delCand) {
      return axiosClient.post('/delete_candidate', delCand)
        .then(({data}) => {
          return data;
        })
    },
    //election send election_id to get result if there are any data
    getElectionResult({commit}, id){
      return axiosClient.post('/election_results', id)
        .then(({data}) => {
          return data;
        })
    },
    //switch of the election status
    switchElectionStatus({commit}, elecId){
      return axiosClient.post('/elecstatus_change', elecId)
        .then(({data})=>{
          return data;
        })
    },
    //update voter's data (tool)
    updateVoter({commit}, data){
      return axiosClient.post('/update_voter', data)
        .then(({data})=>{
          return data;
        })
    },
    //get only sorted data of colleges
    getCollegesSorted(){
      return axiosClient.get('/colleges_sorted')
        .then(({data})=>{
          return data;
        })
    },
    //delete voter account
    deleteVoterAccount({commit}, deleteId){
      return axiosClient.post('/delete_voter', deleteId)
        .then(({data})=>{
          return data;
        })
    },
    createCollegeData({commit}, collegeInfo){
      return axiosClient.post('/create_college', collegeInfo)
        .then(({data})=>{
          return data;
        })
    },
    deleteCollegeData({commit}, deleteInfo){
      return axiosClient.post('/delete_college',deleteInfo)
        .then(({data})=>{
          return data;
        })
    }
  },
  mutations: {
    logout: (state) => {
      state.user.token = null;
      state.user.data = {};
      localStorage.removeItem("TOKEN");
    },
    setUser: (state, userData) => {
      state.user.token = userData.token;
      state.user.data = userData.user;
      localStorage.setItem("TOKEN", userData.token);
    },
    setDataTableRegStud: (state, tableData) => {
      state.registeredVoters.data = tableData.students['id'];
    },
    parseDataFromOrigin: (state, arrayData) => {
      state.tempStudentsRecordHold.data = arrayData.students['id']
    },
  },
  modules: {}
}

//voter functions
const voterModule = {
  state: {
    user: {
      data: {},
      token: localStorage.getItem("TOKEN2"),
    },
  },
  getters: {

  },
  actions: {
    //for getting authenticated voter
    getVoterSession() {
      return axiosClientVoter.get('/voter/user')
        .then(({data}) => {
          return data;
        })
    },
    //login for voter
    voterLogin({commit}, userLogin) {
      return axiosSanctum.get('/sanctum/csrf-cookie')
        .then(response => {
          return axiosClientVoter.post('/voterLogin', userLogin)
            .then(({data}) => {
              commit('setVoterUser', data);
              return data;
            })
        });
    },
    //logout for voter
    voterLogout({commit}, userLogout) {
      return axiosClientVoter.post('/voterLogout', userLogout)
        .then(response => {
          commit('voterLogout');
          return response;
        });
    },
    //for creating voter account
    createVoterAcct({commit}, createVoter) {
      return axiosClientVoter.post('/voter_create', createVoter)
        .then(response => {
          return response;
        })
    },
    //display for select data voter register
    voterGetCollegesData() {
      return axiosClientVoter.get('/colleges_data')
        .then(({data}) => {
          return data;
        })
    },
    //check is the voter is voted or not, or not having an election based in college
    voterViewBallot({commit}, dataCon) {
      return axiosClientVoter.post('/view_election', dataCon)
        .then(response => {
          return response;
        })
    },
    //this is to cast vote from the ballot
    castVoteFromBallot({commit}, castVoteData) {
      return axiosClientVoter.post('/cast_vote', castVoteData)
        .then(response => {
          return response;
        })
    },
    //get sample of user for pushing the election_id for display
    voterGetSample({commit}, id){
      return axiosClientVoter.post('/get_idsample', id)
        .then(response=>{
          return response;
        })
    },
    //voter leaderboard
    voterLeaderboard({commit}, elecId){
      return axiosClientVoter.post('/voting_leaderboard', elecId)
        .then(response=>{
          return response;
        })
    }
  },
  mutations: {
    voterLogout: (state) => {
      state.user.token = null;
      state.user.data = {};
      localStorage.removeItem("TOKEN2");
    },
    setVoterUser: (state, userData) => {
      state.user.token = userData.token;
      state.user.data = userData.user;
      localStorage.setItem("TOKEN2", userData.token);
    },
    setBallotStorage: (state, ballotData) =>{

    }
  },
  modules: {}
}

const store = createStore({
  modules: {
    a: adminModule,
    b: voterModule
  }
})

export default store;
