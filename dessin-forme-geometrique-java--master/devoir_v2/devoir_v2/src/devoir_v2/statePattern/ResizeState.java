package devoir_v2.statePattern;

//import java.awt.event.MouseEvent;
import java.awt.event.*;

import devoir_v2.listenerPattern.ContainerShapes;
import devoir_v2.model.Point;
import devoir_v2.observerPattern.Memento;

public class ResizeState implements State {
	static int selected_shape = -1;

	@Override
	public void mouseClicked(MouseEvent e, ContainerShapes cs) {
		// TODO Auto-generated method stub

	}

	@Override
	public void mouseDragged(MouseEvent e, ContainerShapes cs) {
		// TODO Auto-generated method stub
		for (int i = 0; i < cs.shapes.size(); i++) {
			if (selected_shape == -1) { // if we have not selected a shape yet we should select one
				if ((cs.shapes.get(i).contains(new Point(e.getX(), e.getY())))) {
					selected_shape = i;
				}
			} else {// if we have already selected a shape we should just resize it
				cs.resizeShape(cs.shapes.get(selected_shape), (float) e.getX(), (float) e.getY());
			}
		}

	}

	@Override
	public void mouseReleased(MouseEvent e, ContainerShapes cs) {
		selected_shape = -1;
		// TODO Auto-generated method stub
		Memento.addContainerShapes(cs);// saving this state for later (undo and redo)
	}

	@Override
	public void mousePressed(MouseEvent e, ContainerShapes cs) {
		// TODO Auto-generated method stub

	}

}
