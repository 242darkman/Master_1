package devoir_v2.listenerPattern;

import java.awt.BasicStroke;
import java.awt.Color;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.event.MouseMotionListener;
import javax.swing.JPanel;

import devoir_v2.model.Circle;
import devoir_v2.model.Rectangle;
import devoir_v2.model.Shape;
import devoir_v2.model.Triangle;
import devoir_v2.statePattern.Context;
import devoir_v2.view.CircleView;
import devoir_v2.view.RectangleView;
import devoir_v2.view.ScorePanel;
import devoir_v2.view.TriangleView;

public class ContainerViews extends JPanel implements MouseListener, ChangeListener, MouseMotionListener {
	private static final long serialVersionUID = 1L;

	public ContainerShapes cs;
	Graphics g;
	public Context c;
	public ScorePanel score_panel;

	public ContainerViews(ContainerShapes cs) {// constructor
		this.cs = cs;
	}

	// MOUSE LISTENER
	public void mouseClicked(MouseEvent e) {
		c.getState().mouseClicked(e, cs); // the action we perform during a click depends on the state we are in

	}

	public void mouseEntered(MouseEvent e) {
	}

	public void mouseExited(MouseEvent e) {
	}

	public void mousePressed(MouseEvent e) {
		c.getState().mousePressed(e, cs);// the action we perform during a press depends on the state we are in
	}

	public void mouseReleased(MouseEvent e) {
		c.getState().mouseReleased(e, cs);// the action we perform during a release depends on the state we are in
	}
	// MOUSE MOTION LISTENER

	public void mouseDragged(MouseEvent e) {
		c.getState().mouseDragged(e, cs);// the action we perform during a drag depends on the state we are in
	}

	public void mouseMoved(MouseEvent e) {
	}

	private void drawPoints(Graphics g, ContainerShapes cs) {// method that draws all of our points

		Graphics2D g2d = (Graphics2D) g;

		g2d.setPaint(Color.black);

		for (int i = 0; i < cs.points.length; i++) {
			g2d.setStroke(new BasicStroke((float) 1.7));
			g2d.drawLine((int) cs.points[i].getX(), (int) cs.points[i].getY(), (int) cs.points[i].getX(),
					(int) cs.points[i].getY());

		}

	}

	void drawShapes(Graphics g, ContainerShapes cs) { // method that draws all of our shapes

		for (Shape s : cs.shapes) {
			if (s instanceof Circle) {
				drawCircle(g, (Circle) s);
			}

			if (s instanceof Rectangle) {
				drawRectangle(g, (Rectangle) s);
			}
			if (s instanceof Triangle) {
				drawTriangle(g, (Triangle) s);
			}
		}

	}

	void drawCircle(Graphics g, Circle c) { // method that draws the Circle based on the Circle view
		CircleView vc = new CircleView();
		vc.DrawShape(g, c);
	}

	void drawRectangle(Graphics g, Rectangle r) {// method that draws the Rectangle based on the Rectangle view
		RectangleView vr = new RectangleView();
		vr.DrawShape(g, r);
	}

	void drawTriangle(Graphics g, Triangle t) {// method that draws the Triangle based on the Triangle view
		TriangleView vt = new TriangleView();
		vt.DrawShape(g, t);
	}

	@Override
	public void paintComponent(Graphics g) { // our paintcomponent
		setBackground(Color.lightGray);
		super.paintComponent(g);
		drawPoints(g, cs);
		drawShapes(g, cs);

	}

	public void stateChange(Object source) { // everytime the container changes, it notifies the listener (this class)
		repaint(); // and we execute this function(we repaint our surface and update the score
					// panel
		score_panel.updatePanel(cs, cs.won(), cs.calculate_score(getWidth(), getHeight()));
	}

	/**
	 * @return the c
	 */
	public Context getC() {
		return c;
	}

	/**
	 * @param c the c to set
	 */
	public void setC(Context c) {
		this.c = c;
	}
	
	
	

}
