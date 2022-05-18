package devoir_v2.listenerPattern;

import java.util.ArrayList;

public class ListenableModel {

	protected static ArrayList<ChangeListener> listeners; // listenable model has an array of listeners

	public void addChangeListener(ChangeListener cl) {
		listeners = new ArrayList<ChangeListener>();
		listeners.add(cl);
	}

	public void removeChangeListener(ChangeListener cl) { // methods for removing and adding listeners
		listeners.remove(cl);
	}

	public void fireChange() {
		for (ChangeListener l : listeners) { // method that notifies the listener that changes have been made
			l.stateChange(this);
		}

	}

}
