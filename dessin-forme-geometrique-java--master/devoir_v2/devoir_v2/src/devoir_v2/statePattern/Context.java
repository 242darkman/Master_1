
package devoir_v2.statePattern;

public class Context { // this class has the current state
	private State state;

	public Context() {
		state = new InitialState();
	}

	public void setState(State state) {
		this.state = state;
	}

	public State getState() {
		return state;
	}

}
