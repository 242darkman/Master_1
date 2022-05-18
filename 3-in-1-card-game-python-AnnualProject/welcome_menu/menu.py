#! /usr/bin/python 3.7


import pygame
import sys
from solitaire_one_card.mainSolitaire import main_solitaire
from solitaire_klondike.mainKlondike import main_klondike
from solitaire_yukon.mainYukon import main_yukon

pygame.init()  # initialisation du module pygame

BLACK = 0, 0, 0
WHITE = 255, 255, 255
CIEL = 0, 200, 255
RED = 255, 0, 0
ORANGE = 255, 100, 0
GREEN = 0, 255, 0


class Button:
    '''Ajout d'un bouton avec un texte sur img
    Astuce: ajouter des espaces dans les textes pour avoir une même largeur
    de boutons dx, dy décalage du bouton par rapport au centre
    action si click
    Texte noir
    '''

    def __init__(self, fond, text, color, font, dx, dy):
        self.fond = fond
        self.text = text
        self.color = color
        self.font = font
        self.position = dx, dy
        self.action = False  # enable or not
        self.titre = self.font.render(self.text, True, BLACK)
        textpos = self.titre.get_rect()
        textpos.centerx = self.fond.get_rect().centerx + self.position[0]
        textpos.centery = self.position[1]
        self.textpos = [textpos[0], textpos[1], textpos[2], textpos[3]]
        self.rect = pygame.draw.rect(self.fond, self.color, self.textpos)
        self.fond.blit(self.titre, self.textpos)

    def update_button(self, fond, action=None):
        self.fond = fond
        mouse_xy = pygame.mouse.get_pos()
        over = self.rect.collidepoint(mouse_xy)
        if over:
            action()
            if self.color == RED:
                self.color = GREEN
                self.action = True
            elif self.color == GREEN:
                # sauf les + et -, pour que ce soit toujours vert
                if len(self.text) > 5:  # 5 char avec les espaces
                    self.color = RED
                self.action = False
        # à la bonne couleur
        self.rect = pygame.draw.rect(self.fond, self.color, self.textpos)
        self.fond.blit(self.titre, self.textpos)

    def draw_button(self, fond):
        self.fond = fond
        self.rect = pygame.draw.rect(self.fond, self.color, self.textpos)
        self.fond.blit(self.titre, self.textpos)


class Menu:
    def __init__(self):
        self.quit_button = None
        self.klondike_button = None
        self.yukon_button = None
        self.textes = None
        self.fond = None
        self.solitaire_button = None
        self.width = 1000
        self.height = 600
        self.screen = pygame.display.set_mode((self.width, self.height))
        self.loop = True

        # Définition de la police
        self.big = pygame.font.SysFont('freesans', 48)
        self.small = pygame.font.SysFont('freesans', 36)

        self.creer_fond()
        self.creer_boutton()

    def update_textes(self):
        self.textes = [["3 IN 1 SOLITAIRE", ORANGE, self.big, 0, 50]]

    def creer_fond(self):
        """
        méthode permettant d'inserer une image de fond
        """
        # Image de la taille de la fenêtre
        self.fond = pygame.Surface(self.screen.get_size())

        # background
        bg_img = pygame.image.load('resources/background_menu.jpg')
        bg_img = pygame.transform.scale(bg_img, (self.width, self.height))

        # En bleu
        # self.fond.fill(CIEL)
        self.fond.blit(bg_img, (0, 0))
        pygame.display.set_caption('3 IN 1 SOLITAIRE')

    def creer_boutton(self):
        # left button
        self.solitaire_button = Button(self.fond, "   SOLITAIRE   ", WHITE, self.small, -150, 230)
        self.yukon_button = Button(self.fond, "   YUKON   ", WHITE, self.small, 0, 330)

        # right button
        self.klondike_button = Button(self.fond, "   KLONDIKE   ", WHITE, self.small, 150, 230)

        # center button
        self.quit_button = Button(self.fond, "   QUITTER   ", RED, self.small, 0, 530)

    def display_text(self, text, color, font, dx, dy):
        """Ajout d'un texte sur fond. Décalage dx, dy par rapport au centre.
        """
        mytext = font.render(text, True, color)  # True pour antialiasing
        textpos = mytext.get_rect()
        textpos.centerx = self.fond.get_rect().centerx + dx
        textpos.centery = dy
        self.fond.blit(mytext, textpos)

    def view_menu(self):
        while self.loop:
            self.creer_fond()

            # Boutons gauches
            self.solitaire_button.draw_button(self.fond)
            self.yukon_button.draw_button(self.fond)

            # Boutons droits
            self.klondike_button.draw_button(self.fond)
            self.quit_button.draw_button(self.fond)

            for event in pygame.event.get():
                if event.type == pygame.QUIT:
                    pygame.quit()

                if event.type == pygame.MOUSEBUTTONDOWN:
                    self.solitaire_button.update_button(self.fond, action=run_solitaire)
                    self.yukon_button.update_button(self.fond, action=run_yukon)
                    self.klondike_button.update_button(self.fond, action=run_klondike)
                    self.quit_button.update_button(self.fond, action=closeApp)

            self.update_textes()
            for text in self.textes:
                self.display_text(text[0], text[1], text[2],
                                  text[3], text[4])

            # Ajout du fond dans la fenêtre
            self.screen.blit(self.fond, (0, 0))
            # Actualisation de l'affichage
            # self.fin_de_partie()
            pygame.display.update()
            # 10 fps


def run_solitaire():
    print("Lancement de Solitaire ...")
    main_solitaire()


def run_klondike():
    print("Lancement de Solitaire Klondike ...")
    main_klondike()


def run_yukon():
    print("Lancement de Solitaire Yukon ...")
    main_yukon()


def closeApp():
    print("Application fermée")
    pygame.quit()
    sys.exit()


def bouton_home(self, fond):
    return Button(fond, "   Home   ", RED, self.small, 0, 530)


if __name__ == '__main__':
    game = Menu()
    game.view_menu()
