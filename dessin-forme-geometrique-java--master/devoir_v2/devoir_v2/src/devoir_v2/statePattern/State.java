package devoir_v2.statePattern;

import java.awt.event.MouseEvent;

import devoir_v2.listenerPattern.ContainerShapes;

//import java.util.EventListener;

public interface State {
	// Different states expected
	// create, delete, Move, resize
	public void mousePressed(MouseEvent e, ContainerShapes cs);

	public void mouseReleased(MouseEvent e, ContainerShapes cs);

	public void mouseClicked(MouseEvent e, ContainerShapes cs);

	public void mouseDragged(MouseEvent e, ContainerShapes cs);// all the mouse events we need are clicking, dragging
																// and releasing

}
