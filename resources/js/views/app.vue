<template>
  <div>
    <div class="row">
      <div class="col">
        <h1>Column Scorecard</h1>
      </div>
    </div>
    <div v-if="message" class="alert alert-info">
        <p>
            {{message}}
        </p>
        <button class="btn btn-primary btn-sm" v-on:click="message = ''">
            Clear Message
        </button>
    </div>
    <div class="row">
      <div class="col-sm-6 mx-auto">
        <div class="card">
          <div class="card-body">
            <div class="row">
                <div class="col my-1">
                    <label class="form-label">
                        Name
                    </label>
                    <input class="form-control" v-model="name">
                    <div class="invalid-feedback d-block" v-if="nameErrors">
                        {{nameErrors}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col my-1">
                    <label class="form-label">
                        Card Selections
                    </label>
                    <input class="form-control" v-model="cards">
                    <div class="invalid-feedback d-block" v-if="cardErrors">
                        {{cardErrors}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col my-1">
                    <button class="btn btn-primary" v-on:click="play">
                        Play
                    </button>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row my-3">
      <div class="col-sm-6 mx-auto">
        <div class="card">
          <div class="card-header">
            Leaderboard
          </div>
          <div class="card-body">
            <div class="row">
                <div class="col my-1">
                    <table class="table table-striped">
                      <tr>
                        <th>Name</th>
                        <th>Games Won</th>
                      </tr>
                      <tr v-for="entry in leaderboard" :key="entry.name">
                        <td>
                          {{entry.name}}
                        </td>
                        <td>
                          {{entry.score}}
                        </td>
                      </tr>
                    </table>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <hr />
      </div>
    </div>
    <div class="row" v-if="showInstructions">
        <div class="col alert alert-info">
            <p>
                Welcome to the exciting game of Column Scorecard!  In this game, you will select a number of cards,
                and I will then select the same nummber of cards.  Then, we will look to see which card in each column is larger.
                The larger card will get a point, and the score will be tallied.
            </p>
            <p>
                Valid cards, in order of value, are:
            </p>
            <h5 class="text-center">
                2 3 4 5 6 7 8 9 10 J Q K A
            </h5>
            <p>
                Good luck!
            </p>
            <button class="btn btn-primary btn-sm" v-on:click="hideInstructionsClick">
                Hide Instructions
            </button>
        </div>
    </div>
    <div class="row" v-if="!showInstructions">
      <div class="col">
        <button class="btn btn-primary btn-sm" v-on:click="showInstructionsClick">
          Instructions
        </button>
      </div>
    </div>
  </div>
</template>
<script>
const default_layout = "default";


export default {
  computed: {},
  data() {
      return {
          message:'',
          showInstructions: false,
          name: "",
          nameErrors: "",
          cards: "",
          cardErrors: "",
          leaderboard: [],
      }
  },
  methods: {
    clearMessage: function() {
        this.message = "";
    },
    showInstructionsClick: function() {
        this.showInstructions = true;
    },
    hideInstructionsClick: function() {
        this.showInstructions = false;
    },
    play: function() {
        this.nameErrors = '';
        this.cardErrors = '';
        axios.post('/api/hand', {
            name: this.name,
            cards: this.cards
        }).then(function(response) {
            this.message = "Computer played: " + response.data.data.computer_play +
                "!  Your score was " + response.data.data.user_score +
                " vs the computer's score of " + response.data.data.computer_score;
            if(response.data.data.user_won) {
                this.message += " CONGRATULATIONS!!!!  YOU WIN!!!";
            }
            this.loadLeaderboard();
        }.bind(this)).catch(function(error) {
            switch(error.response.status) {
                case 422:
                    this.message = error.response.data.message;
                    if(error.response.data.errors.name) {
                        this.nameErrors = error.response.data.errors.name.join('; ');
                    }
                    if(error.response.data.errors.cards) {
                        this.cardErrors = error.response.data.errors.cards.join('; ');
                    }
                    break;
                default:
                    console.log(error);
                    break;
            }
        }.bind(this))
    },
    loadLeaderboard: function() {
        axios.get('/api/leaderboard')
            .then(function(response) {
                console.log(response.data);
                this.leaderboard = response.data;
            }.bind(this))
            .catch(function(error) {
                console.log(error)
            })
    }
  }
};
</script>