package devoir_v2.statePattern;

//import java.awt.event.MouseEvent;
//import java.awt.*;
import java.awt.event.*;

import devoir_v2.listenerPattern.ContainerShapes;
import devoir_v2.model.Point;
import devoir_v2.observerPattern.Memento;

public class DeleteState implements State {

	@Override
	public void mouseClicked(MouseEvent e, ContainerShapes cs) {
		for (int i = 0; i < cs.shapes.size(); i++) {

			if (cs.shapes.get(i).contains(new Point(e.getX(), e.getY()))) {

				cs.removeShape(cs.shapes.get(i));// deletes the shape that was selected by the cursor
			}
		}
		Memento.addContainerShapes(cs); // saving this state for later (undo and redo)

	}

	public void mouseDragged(MouseEvent e, ContainerShapes cs) {
	}

	@Override
	public void mousePressed(MouseEvent e, ContainerShapes cs) {
		// TODO Auto-generated method stub

	}

	@Override
	public void mouseReleased(MouseEvent e, ContainerShapes cs) {
		// TODO Auto-generated method stub

	}

}
