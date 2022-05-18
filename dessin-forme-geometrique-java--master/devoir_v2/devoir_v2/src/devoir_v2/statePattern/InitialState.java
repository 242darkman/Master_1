package devoir_v2.statePattern;

import java.awt.event.MouseEvent;

import devoir_v2.listenerPattern.ContainerShapes;

public class InitialState implements State {

	@Override
	public void mouseClicked(MouseEvent e, ContainerShapes cs) {
		// we do nothing at first
	}

	public void mouseDragged(MouseEvent e, ContainerShapes cs) {
	}

	@Override
	public void mouseReleased(MouseEvent e, ContainerShapes cs) {
		// TODO Auto-generated method stub

	}

	@Override
	public void mousePressed(MouseEvent e, ContainerShapes cs) {
		// TODO Auto-generated method stub

	}

}
