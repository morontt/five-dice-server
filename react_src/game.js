var PlayerBox = React.createClass({

    render: function () {
        return (
            <div className="row player-box">
                <div className="col-md-12">
                    <h4>Player <em>{this.props.name}</em></h4>
                </div>
            </div>
        );
    }

});

var Tile = React.createClass({
    render: function () {
        var elementClasses = 'dot dot-' + this.props.dot;

        return <span className={elementClasses}></span>
    }
});

var Tiles = React.createClass({
    render: function () {
        var key = 0;
        var tileNodes = this.props.dots.map(function (dot) {
            key++;
            return (
                <Tile key={key} dot={dot}/>
            );
        });

        return (
            <div className="row tiles">
                <div className="col-md-12">
                    {tileNodes}
                    <button onClick={this.props.handleRoll}>Roll!</button>
                </div>
            </div>
        );
    }
});

var DiceApp = React.createClass({
    getInitialState: function () {
        return {
            dots: [0, 0, 0, 0, 0]
        };
    },

    getRandomDots: function() {
        var result = [];
        for (var i = 1; i <= 5; i++) {
            result.push(this.getRandomInt(1, 6));
        }

        return result;
    },

    getRandomInt: function (min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min
    },

    handleRoll : function () {
        this.setState({dots: this.getRandomDots()});
    },

    render: function () {
        return (
            <div className="row">
                <div className="col-md-12">
                    <PlayerBox name='Hawkeye Pierce' />
                    <Tiles dots={this.state.dots} handleRoll={this.handleRoll}/>
                    <PlayerBox name='Trapper John McIntyre' />
                </div>
            </div>
        );
    }
});

ReactDOM.render(
    <DiceApp />,
    document.getElementById('game-app')
);
