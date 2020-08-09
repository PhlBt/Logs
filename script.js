new Vue({
  el: '#app',
  vuetify: new Vuetify(),
  data() {
    return {
      file: undefined,
      fileName: '',
      search:'',
      headers: [
        { text: 'ID', value: 'id' },
        { text: 'status', value: 'status' },
        { text: 'payload', value: 'path' },
        { text: 'url', value: 'url' },
      ],
      fileHeaders: [
        { text: 'Файл', value: 'file' },
        { text: '', value: 'action', sortable: false, align: 'end' },
      ],
      items: [],
      files: []
    }
  },
  methods: {
    downloadCSV: function(name) {
      
    },
    uploadFile: function(files) {
      this.sendFile(this.file, this.fileName).then( response => {
        console.log(response.message)
        this.file = undefined
        this.fileName = ''
        this.getFileList().then( data => this.files = data )
      })
    },
    showFile: function(name) {
      this.getFile(name).then( response => {
        console.log(response.message)
        this.items = response.data
      })
    },
    deleteFile: function (name) {
      this.getDeleteFile(name).then(()=>{
        this.getFileList().then( data => this.files = data )
      })
    },
    getDeleteFile: async function(name) {
      let response = await fetch('/script.php?action=delete&name='+name, {
        method: 'GET'
      });
      return await response.json()
    },
    sendFile: async function(file, name) {
      let formData = new FormData()
      formData.append('file', file)

      let response = await fetch('/script.php?action=upload&file_name='+name, {
        method: 'POST',
        body: formData
      });

      return await response.json()
    },
    getFileList: async function() {
      let response = await fetch('/script.php?action=list', {
        method: 'GET'
      });
      return await response.json()
    },
    getFile: async function(name) {
      let response = await fetch('/script.php?action=file&name='+name, {
        method: 'GET'
      });
      return await response.json()
    }
  },
  created() {
    this.getFileList().then( data => this.files = data )
  }
})
